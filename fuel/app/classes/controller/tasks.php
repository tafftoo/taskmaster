<?php
class Controller_Tasks extends Controller_Base
{
	public function action_view()
	{
		$task_id = $this->params('task_id');
		$task = Model_Task::find($task_id);
		if ($task && $this->current_user->canView($task))
		{
			$this->template->title = $task->title . ' assigned by ' . $task->originator->username;
			$view = ViewModel::forge('tasks/view');
			$view->set('task', $task, false);

			$this->template->content = $view;
		}
		else
		{
			throw new HttpNotFoundException();
		}
	}

	public function action_performaction()
	{
		$required_action = $this->param('action');
		$task_id = $this->param('task_id');
		$task = Model_Task::find($task_id);
		if ($task && $this->current_user->canView($task)) {
			switch($required_action) {
			case 'start':
				$task->status = Model_Task::$STATUS_IN_PROGRESS;
				$task->started_at = time();
				break;
			case 'pause':
				$task->status = Model_Task::$STATUS_PAUSED;
				break;
			case 'resume':
				$task->status = Model_Task::$STATUS_IN_PROGRESS;
				break;
			case 'complete':
				$task->status = Model_Task::$STATUS_COMPLETE;
				$task->completed_at = time();
				break;
			default:
				throw new HttpNotFoundException();
			}
			$task->save();
			Response::redirect('/task/' . $task->id);
		}
		else
		{
			throw new HttpNotFoundException();
		}
	}

	public function action_delegate()
	{
		$this->template->title = 'Choose a slave';
		$this->template->content = ViewModel::forge('tasks/delegate');
	}

	public function action_delegateto()
	{
		$username = $this->param('slave');
		$slave = Model_User::query()->where('username', $username)->get_one();
		if ($slave && $this->current_user->isMasterOf($slave)) 
		{
			$view = ViewModel::forge('tasks/delegateto');
			$view->set('current_user', $this->current_user, false);
			$view->set('slave', $slave, false);

			$form = $view->buildTaskForm(Input::post());

			if (Input::post()) {
				$val = $form->validation();
				if ($val->run()) {
					$values = $val->validated();

					$values['due_at'] = (!empty($values['due_at'])) ? strtotime($values['due_at']) : 0;
					$values['completed_at'] = (!empty($values['completed_at'])) ? strtotime($values['completed_at']) : 0;
					$values['started_at'] = (!empty($values['started_at'])) ? strtotime($values['started_at']) : 0;

					$newTask = Model_Task::forge($values);

					$newTask->save();

					Response::redirect('/user/' . $username);
				} else {
					die('Form did not validate');
				}
			}
			

			$this->template->title = 'Delegate task to ' . $username;
			$this->template->content = $view;
		} 
		else
		{
			throw new HttpServerErrorException('Not your slave');
		}
	}
}
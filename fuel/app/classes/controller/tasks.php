<?php
class Controller_Tasks extends Controller_Base
{
	public function action_delegate()
	{

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
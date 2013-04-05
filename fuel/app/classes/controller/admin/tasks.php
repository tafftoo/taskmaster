<?php
class Controller_Admin_Tasks extends Controller_Admin 
{

	public function action_index()
	{
		$data['tasks'] = Model_Task::find('all');
		$this->template->title = "Tasks";
		$this->template->content = View::forge('admin/tasks/index', $data);

	}

	public function action_view($id = null)
	{
		$data['task'] = Model_Task::find($id);

		$this->template->title = "Task";
		$this->template->content = View::forge('admin/tasks/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Task::validate('create');

			if ($val->run())
			{
				$task = Model_Task::forge(array(
					'title' => Input::post('title'),
				));

				if ($task and $task->save())
				{
					Session::set_flash('success', e('Added task #'.$task->id.'.'));

					Response::redirect('admin/tasks');
				}

				else
				{
					Session::set_flash('error', e('Could not save task.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Tasks";
		$this->template->content = View::forge('admin/tasks/create');

	}

	public function action_edit($id = null)
	{
		$task = Model_Task::find($id);
		$val = Model_Task::validate('edit');

		if ($val->run())
		{
			$task->title = Input::post('title');

			if ($task->save())
			{
				Session::set_flash('success', e('Updated task #' . $id));

				Response::redirect('admin/tasks');
			}

			else
			{
				Session::set_flash('error', e('Could not update task #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$task->title = $val->validated('title');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('task', $task, false);
		}

		$this->template->title = "Tasks";
		$this->template->content = View::forge('admin/tasks/edit');

	}

	public function action_delete($id = null)
	{
		if ($task = Model_Task::find($id))
		{
			$task->delete();

			Session::set_flash('success', e('Deleted task #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete task #'.$id));
		}

		Response::redirect('admin/tasks');

	}


}
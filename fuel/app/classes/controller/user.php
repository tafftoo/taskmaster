<?php
class Controller_User extends Controller_Base
{
	public function action_index()
	{
		$view = ViewModel::forge('user/index');
		$view->show_sidepanel = true; 		// @TODO: Make this user configurable

		$this->template->title = $this->current_user->username;
		$this->template->content = $view;
	}

	public function action_viewuser()
	{
		$user = Model_User::query()->where('username', $this->param('username'))->get_one();

		if ($user) 
		{
			$this->template->title = $user->username;

			$view = ViewModel::forge('user/user');
			$view->set('user', $user, false);
			$view->show_sidepanel = true;		// @TODO: make this a personal preference of the user

			$this->template->content = $view;
		}
		else
		{
			throw new HttpNotFoundException();
		}
	}
}
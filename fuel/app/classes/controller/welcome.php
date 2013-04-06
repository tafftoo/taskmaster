<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.5
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Welcome extends Controller_Base
{

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		$this->template->title = 'Task Master';
		$this->template->content = ViewModel::forge('welcome/index');
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(ViewModel::forge('welcome/404'), 404);
	}

	public function action_login()
	{
		$val = Validation::forge();

		if (Input::method() == 'POST')
		{
			$val->add('email', 'Email or Username')
			    ->add_rule('required');
			$val->add('password', 'Password')
			    ->add_rule('required');

			if ($val->run())
			{
				$auth = Auth::instance();

				// check the credentials. This assumes that you have the previous table created
				if (Auth::check() or $auth->login(Input::post('email'), Input::post('password')))
				{
					// credentials ok, go right in
					$current_user = Model_User::find_by_username(Auth::get_screen_name());
					Session::set_flash('success', e('Welcome, '.$current_user->username));
					Response::redirect('/welcome');
				}
				else
				{
					$this->template->set_global('login_error', 'Fail');
				}
			}
		}

		$this->template->title = 'Login';
		$this->template->content = View::forge('welcome/login', array('val' => $val), false);
	}

	public function action_logout()
	{
		Auth::logout();
		Response::redirect('/welcome');		
	}

}

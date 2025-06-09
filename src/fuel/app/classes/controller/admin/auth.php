<?php

class Controller_Admin_Auth extends Controller_Admin_Common_Auth
{
	public function action_login()
	{
		if (Input::method() == 'POST') {
			$val = Validation::forge();

			$val->add('email', 'email')->add_rule('required');
			$val->add('password', 'password')->add_rule('required');
	
			if ($val->run()) {
				$email = Input::post('email');
				$password = Input::post('password');

				if (Auth::login($email, $password)) {
					$previous_url = Input::post('previous_url');
					$previous_url = $previous_url == '' ? '/admin/products' : $previous_url;

					Response::redirect($previous_url);
				} else {
					Session::set_flash('msg_error', 'The username or password you entered is incorrect.');
				}
			} else {
				Session::set_flash('errors', $val->error());
			}
		}

		return Response::forge(View::forge('admin/auth/login'));
	}

	public function action_logout()
	{
		Auth::logout();

		Response::redirect('/admin/login');
	}
}

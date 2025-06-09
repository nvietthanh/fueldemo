<?php

class Controller_Admin_Common_Auth extends Controller_Base
{
	public function before()
	{
		parent::before();

		$uri = Uri::string();
		$is_logged_in = Auth::check();

		if (!$is_logged_in && $uri !== 'admin/login') {
			$previous_url = Uri::current();
			$login_url = 'admin/login?previous_url=' . urlencode($previous_url);

			Response::redirect($login_url);
		}

		if ($is_logged_in && $uri === 'admin/login') {
			Response::redirect('admin/products');
		}
	}
}

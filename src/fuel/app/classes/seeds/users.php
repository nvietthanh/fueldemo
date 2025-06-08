<?php

use Auth\Auth;

class Seeds_Users
{
	public static function run()
	{
		$auth = Auth::instance();
		$password = $auth->hash_password('12345678', 'users');

		DB::insert('users')
			->columns(['username', 'password', 'group', 'email', 'last_login', 'login_hash', 'profile_fields', 'created_at', 'updated_at'])
			->values(['user', $password, 100, 'user@example.com', null, null, '', time(), time()])
			->execute();

		echo "✔ Seeded users\n";
	}
}

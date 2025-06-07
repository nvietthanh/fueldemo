<?php

class Seeds_Admins
{
	public static function run()
	{
		$auth = Auth::instance();
		$password = $auth->hash_password('admin123', 'admins');

		\DB::insert('admins')
			->columns(['username', 'password', 'group', 'email', 'last_login', 'login_hash', 'profile_fields', 'created_at', 'updated_at'])
			->values(['admin', $password, 100, 'admin@example.com', null, null, '', time(), time()])
			->execute();

		echo "✔ Seeded admins\n";
	}
}

<?php

namespace MyAuth;

class Auth_Login_SimpleAuth extends \Auth\Auth_Login_Simpleauth
{
	var $configname = '';

	public function set_configname(?string $configname = null)
	{
		$this_controller = \Request::active()->controller ?? null;

		if ($this_controller) {
			if (preg_match('/^Controller_Admin_/', $this_controller)) {
				$this->configname = 'admins';
			} else {
				$this->configname = 'users';
			}
		}
	}

	/**
	 * Check for login
	 * @return  bool
	 **/
	protected function perform_check()
	{
		$this->set_configname();

		$username    = \Session::get(\Config::get("myauth.{$this->configname}.username"));
		$login_hash  = \Session::get(\Config::get("myauth.{$this->configname}.login_hash"));

		// only worth checking if there's both a username and login-hash
		if (!empty($username) and !empty($login_hash)) {
			if (is_null($this->user) or ($this->user['username'] != $username and $this->user != static::$guest_login)) {
				$this->user = \DB::select_array(\Config::get("myauth.{$this->configname}.table_columns", array('*')))
					->where('username', '=', $username)
					->from(\Config::get("myauth.{$this->configname}.table_name"))
					->execute(\Config::get("myauth.{$this->configname}.db_connection"))->current();
			}
			// return true when login was verified
			if ($this->user and $this->user['login_hash'] === $login_hash) {
				return true;
			}
		}
		// no valid login when still here, ensure empty session and optionally set guest_login
		$this->user = \Config::get("myauth.{$this->configname}.guest_login", true) ? static::$guest_login : false;
		\Session::delete(\Config::get("myauth.{$this->configname}.username"));
		\Session::delete(\Config::get("myauth.{$this->configname}.login_hash"));
		return false;
	}

	public function create_user($username, $password, $email, $group = 0, array $profile_fields = array())
	{
		$password = trim($password);

		$same_users = \DB::select_array(\Config::get("myauth.{$this->configname}.table_columns", array('*')))
			->where('username', '=', $username)
			->from(\Config::get("myauth.{$this->configname}.table_name"))
			->execute(\Config::get("myauth.{$this->configname}.db_connection"));

		if ($same_users->count() > 0) {
			if (in_array(strtolower($email), array_map('strtolower', $same_users->current()))) {
				throw new \SimpleUserUpdateException('Email address already exists', 2);
			} else {
				throw new \SimpleUserUpdateException('Username already exists', 3);
			}
		}

		$user = array(
			'username'        => (string) $username,
			'password'        => $this->hash_password((string) $password),
			'email'           => $email,
			'group'           => (int) $group,
			'profile_fields'  => serialize($profile_fields),
			'last_login'      => 0,
			'login_hash'      => '',
			'created_at'      => \Date::forge()->get_timestamp(),
		);
		$result = \DB::insert(\Config::get("myauth.{$this->configname}.table_name"))
			->set($user)
			->execute(\Config::get("myauth.{$this->configname}.db_connection"));

		return ($result[1] > 0) ? $result[0] : false;
	}

	public function update_user($values, $username = null)
	{
		$username = $username ?: $this->user['username'];
		$current_values = \DB::select_array(\Config::get("myauth.{$this->configname}.table_columns", array('*')))
			->where('username', '=', $username)
			->from(\Config::get("myauth.{$this->configname}.table_name"))
			->execute(\Config::get("myauth.{$this->configname}.db_connection"));

		if (empty($current_values)) {
			throw new \SimpleUserUpdateException('Username not found', 4);
		}

		$update = array();
		if (array_key_exists('username', $values)) {
			throw new \SimpleUserUpdateException('Username cannot be changed.', 5);
		}
		if (array_key_exists('password', $values)) {
			if (empty($values['old_password']) or $current_values->get('password') != $this->hash_password(trim($values['old_password']))) {
				throw new \SimpleUserWrongPassword('Old password is invalid');
			}

			$password = trim(strval($values['password']));
			if ($password === '') {
				throw new \SimpleUserUpdateException('Password can\'t be empty.', 6);
			}
			$update['password'] = $this->hash_password($password);
			unset($values['password']);
		}
		if (array_key_exists('old_password', $values)) {
			unset($values['old_password']);
		}
		if (array_key_exists('email', $values)) {
			$email = filter_var(trim($values['email']), FILTER_VALIDATE_EMAIL);
			if (!$email) {
				throw new \SimpleUserUpdateException('Email address is not valid', 7);
			}
			$update['email'] = $email;
			unset($values['email']);
		}
		if (array_key_exists('group', $values)) {
			if (is_numeric($values['group'])) {
				$update['group'] = (int) $values['group'];
			}
			unset($values['group']);
		}
		if (!empty($values)) {
			$profile_fields = @unserialize($current_values->get('profile_fields')) ?: array();
			foreach ($values as $key => $val) {
				if ($val === null) {
					unset($profile_fields[$key]);
				} else {
					$profile_fields[$key] = $val;
				}
			}
			$update['profile_fields'] = serialize($profile_fields);
		}

		$affected_rows = \DB::update(\Config::get("myauth.{$this->configname}.table_name"))
			->set($update)
			->where('username', '=', $username)
			->execute(\Config::get("myauth.{$this->configname}.db_connection"));

		// Refresh user
		if ($this->user['username'] == $username) {
			$this->user = \DB::select_array(\Config::get('myauth.{$this->configname}.table_columns', array('*')))
				->where('username', '=', $username)
				->from(\Config::get("myauth.{$this->configname}.table_name"))
				->execute(\Config::get("myauth.{$this->configname}.db_connection"))
				->current();
		}
		return $affected_rows > 0;
	}

	/**
	 * Check the user exists
	 *
	 * @return  bool
	 */
	public function validate_user($username_or_email = '', $password = '')
	{
		$username_or_email = trim($username_or_email) ?: trim(\Input::post(\Config::get("myauth.{$this->configname}.username_post_key", 'username')));
		$password = trim($password) ?: trim(\Input::post(\Config::get("myauth.{$this->configname}.password_post_key", 'password')));

		if (empty($username_or_email) or empty($password)) {
			return false;
		}

		$password = $this->hash_password($password);

		$query = \DB::select_array(\Config::get("myauth.{$this->configname}.table_columns", array('*')))
			->where('password', '=', $password)
			->where_open()
			->where('username', '=', $username_or_email)
			->or_where('email', '=', $username_or_email)
			->where_close();

		$user = $query->from(\Config::get("myauth.{$this->configname}.table_name"))
			->execute(\Config::get("myauth.{$this->configname}.db_connection"))
			->current();

		return $user ?: false;
	}

	/**
	 * Creates a temporary hash that will validate the current login
	 *
	 * @return  string
	 */
	public function create_login_hash()
	{
		if (empty($this->user)) {
			throw new \SimpleUserUpdateException('User not logged in, can\'t create login hash.', 10);
		}

		$last_login = \Date::forge()->get_timestamp();
		$login_hash = sha1(\Config::get("myauth.{$this->configname}.login_hash_salt") . $this->user['username'] . $last_login);

		\DB::update(\Config::get("myauth.{$this->configname}.table_name"))
			->set(array('last_login' => $last_login, 'login_hash' => $login_hash))
			->where('username', '=', $this->user['username'])
			->execute(\Config::get("myauth.{$this->configname}.db_connection"));

		$this->user['login_hash'] = $login_hash;

		return $login_hash;
	}

	/**
	 * Generates new random password, sets it for the given username and returns the new password.
	 * To be used for resetting a user's forgotten password, should be emailed afterwards.
	 *
	 * @param   string  $username
	 * @return  string
	 */
	public function reset_password($username)
	{
		$new_password = \Str::random('alnum', 8);
		$password_hash = $this->hash_password($new_password);

		$affected_rows = \DB::update(\Config::get("myauth.{$this->configname}.table_name"))
			->set(array('password' => $password_hash))
			->where('username', '=', $username)
			->execute(\Config::get("myauth.{$this->configname}.db_connection"));
		if (!$affected_rows) {
			//		throw new \SimpleUserUpdateException('Failed to reset password, user was invalid.', 8);
		}

		return $new_password;
	}

	/**
	 * Default password hash method
	 *
	 * @param   string
	 * @param	string confignameを指定してhashする(agentからentryのパスワードを変更する際などに利用)
	 * @return  string
	 */
	public function hash_password($password, $auth_type = '')
	{
		if ($auth_type == '') {
			$auth_type = $this->configname;
		}
		return base64_encode(hash_pbkdf2('sha256', $password, \Config::get("myauth.{$auth_type}.login_hash_salt"), \Config::get('auth.iterations', 10000), 32, true));
	}

	/**
	 * Deletes a given user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function delete_user($username)
	{
		if (empty($username)) {
			throw new \SimpleUserUpdateException('Cannot delete user with empty username', 9);
		}

		$affected_rows = \DB::delete(\Config::get("myauth.{$this->configname}.table_name"))
			->where('username', '=', $username)
			->execute(\Config::get("myauth.{$this->configname}.db_connection"));

		return $affected_rows > 0;
	}

	/**
	 * Login user
	 *
	 * @param   string
	 * @param   string
	 * @return  bool
	 */
	public function login($username_or_email = '', $password = '')
	{
		if (!($this->user = $this->validate_user($username_or_email, $password))) {
			$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
			\Session::delete(\Config::get("myauth.{$this->configname}.username"));
			\Session::delete(\Config::get("myauth.{$this->configname}.login_hash"));

			return false;
		}

		// register so Auth::logout() can find us
		\Auth::_register_verified($this);
		\Session::set(\Config::get("myauth.{$this->configname}.username"), $this->user['username']);
		\Session::set(\Config::get("myauth.{$this->configname}.login_hash"), $this->create_login_hash());
		\Session::instance()->rotate();

		return true;
	}

	/**
	 * Force login user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function force_login($user_id = '')
	{
		if (empty($user_id)) {
			return false;
		}

		$this->user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
			->where_open()
			->where('id', '=', $user_id)
			->where_close()
			->from(\Config::get('simpleauth.table_name'))
			->execute(\Config::get('simpleauth.db_connection'))
			->current();

		if ($this->user == false) {
			$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
			\Session::delete(\Config::get("myauth.{$this->configname}.username"));
			\Session::delete(\Config::get("myauth.{$this->configname}.login_hash"));
			return false;
		}

		\Session::set(\Config::get("myauth.{$this->configname}.username"), $this->user['username']);
		\Session::set(\Config::get("myauth.{$this->configname}.login_hash"), $this->create_login_hash());

		// and rotate the session id, we've elevated rights
		\Session::instance()->rotate();

		// register so Auth::logout() can find us
		Auth::_register_verified($this);

		return true;
	}

	/**
	 * Logout user
	 *
	 * @return  bool
	 */
	public function logout()
	{
		$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
		\Session::delete(\Config::get("myauth.{$this->configname}.username"));
		\Session::delete(\Config::get("myauth.{$this->configname}.login_hash"));
		return true;
	}
}

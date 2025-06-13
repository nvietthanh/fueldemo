<?php

/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

return  array(
    'users' => array(
        'db_connection'        => null,
        'table_name'        => 'users',
        'table_columns'        => array('*'),
        'guest_login'        => true,
        'login_hash_salt'    => 'login_hash_salt_user',
        'username_post_key'    => 'email',
        'password_post_key'    => 'password',
        'username'            => 'user_username',
        'login_hash'        => 'user_loginhash',
    ),
    'admins' => array(
        'db_connection'        => null,
        'table_name'        => 'admins',
        'table_columns'        => array('*'),
        'guest_login'        => true,
        'login_hash_salt'    => 'login_hash_salt_admin',
        'username_post_key' => 'email',
        'password_post_key' => 'password',
        'username'            => 'admin_username',
        'login_hash'        => 'admin_loginhash',
    ),
);

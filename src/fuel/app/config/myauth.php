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
        'login_hash_salt'    => 'jdjck5eiemfmvsamc5keeiog2sd4g1851asoljfgdo84kldl',
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
        'login_hash_salt'    => 'jc93jcng834owaamcvbaoq945n67wrqfn85nfvl3kfkn923m0ama8',
        'username_post_key' => 'email',
        'password_post_key' => 'password',
        'username'            => 'admin_username',
        'login_hash'        => 'admin_loginhash',
    ),
);

<?php

/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

/**
 * -----------------------------------------------------------------------------
 *  Global database settings
 * -----------------------------------------------------------------------------
 *
 *  Set database configurations here to override environment specific
 *  configurations
 *
 */

return array(
    'redis' => array(
        'default' => array(
            'hostname'     => env('REDIS_HOST', '127.0.0.1'),
            'port'     => env('REDIS_PORT', '6379'),
            'password' => env('REDIS_PASSWORD'),
            'database' => (int) env('REDIS_DATABASE', 0),
            'timeout'  => 2.5,
        ),
    ),
);

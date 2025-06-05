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

$common = array(
	'_404_' => 'welcome/404',
);

$admin = array(
	// authenticate
	'admin/login' => 'admin/auth/login',
	'admin/logout' => 'admin/auth/logout',

	// manage product
	'admin/products' => array(
		array('GET', new Route('admin/products/index')),
		array('POST', new Route('admin/products/store')),
	),

	'admin/products/create' => array(
		array('GET', new Route('admin/products/create')),
	),
	'admin/products/(:num)/edit' => array(
		array('GET', new Route('admin/products/edit/$1')),
	),
	'admin/products/(:num)' => array(
		array('POST', new Route('admin/products/update/$1')),
	),
	'admin/products/(:num)' =>array(
		array('DELETE', new Route('admin/products/delete/$1')),
	),

	// manage category
	'admin/categories' => array(
		array('GET', new Route('admin/categories/index')),
		array('POST', new Route('admin/categories/store')),
	),
	'admin/categories/(:num)' => array(
		array('GET', new Route('admin/categories/show/$1')),
		array('POST', new Route('admin/categories/update/$1')),
		array('DELETE', new Route('admin/categories/delete/$1')),
	),
);

return array_merge($common, $admin);

<?php

class Model_Order extends \Orm\Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"user_id" => array(
			"label" => "User id",
			"data_type" => "int",
		),
		"customer" => array(
			"label" => "Customer",
			"data_type" => "json",
		),
		"created_at" => array(
			"label" => "Created at",
			"data_type" => "int",
		),
		"updated_at" => array(
			"label" => "Updated at",
			"data_type" => "int",
		),
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'property' => 'created_at',
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'property' => 'updated_at',
			'mysql_timestamp' => false,
		),
	);

	protected static $_table_name = 'orders';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
		'orderProducts' => [
			'key_from' => 'id',
			'model_to' => 'Model_Order_Product',
			'key_to' => 'order_id',
			'cascade_save' => false,
			'cascade_delete' => false,
		],
	);

	protected static $_many_many = array();

	protected static $_has_one = array();

	protected static $_belongs_to = array(
		'user' => [
			'key_from' => 'user_id',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => false,
			'cascade_delete' => false,
		],
	);
}

<?php

class Model_Order_Product extends \Orm\Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"order_id" => array(
			"label" => "Order id",
			"data_type" => "int",
		),
		"name" => array(
			"label" => "Name",
			"data_type" => "varchar",
		),
		"product_id" => array(
			"label" => "Product id",
			"data_type" => "int",
		),
		"quantity" => array(
			"label" => "Quantity",
			"data_type" => "int",
		),
		"price" => array(
			"label" => "Price",
			"data_type" => "int",
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

	protected static $_table_name = 'order_products';

	protected static $_primary_key = array('id');

	protected static $_has_many = array();

	protected static $_many_many = array();

	protected static $_has_one = array();

	protected static $_belongs_to = array();

	public function get_total()
    {
        return $this->quantity * $this->price;
    }
}

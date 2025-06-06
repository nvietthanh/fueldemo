<?php

use Fuel\Core\Validation;

class Requests_Admin_Product_Update extends Requests_Common_Base
{
    public static function setValidator(Validation $val): Validation
    {
		$val->add('name', 'name')->add_rule('required');
		$val->add('price', 'price')->add_rule('required');
		$val->add('quantity', 'quantity')->add_rule('required');
		$val->add('category_id', 'category id')->add_rule('required');
		$val->add('description', 'description')->add_rule('required');
		$val->add('image_file', 'image');

		return $val;
    }
}

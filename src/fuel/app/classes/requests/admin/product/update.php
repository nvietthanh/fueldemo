<?php

use Fuel\Core\Validation;

class Requests_Admin_Product_Update extends Requests_Common_Base
{
    public static function setValidator(Validation $val): Validation
    {
		$val->add('name', 'name')
			->add_rule('required')
			->add_rule('max_length', 255);
		$val->add('price', 'price')
			->add_rule('required')
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 1000000000);
		$val->add('quantity', 'quantity')
			->add_rule('required')
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 1000000);
		$val->add('category_id', 'category id')
			->add_rule('required')
			->add_rule('category_exists');
		$val->add('description', 'description')
			->add_rule('required')
			->add_rule('max_length', 255);
		$val->add('image_file', 'image')
			->add_rule('valid_image_file_error')
			->add_rule('valid_image_file_type')
			->add_rule('valid_image_file_size');

		return $val;
    }
}

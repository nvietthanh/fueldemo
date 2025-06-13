<?php

use Fuel\Core\Validation;

class Requests_User_Cart_Update extends Requests_Common_Base
{
    public static function set_validator(Validation $val): Validation
    {
        $val->add('product_id', 'product id')->add_rule('required');
        $val->add('quantity', 'quantity')->add_rule('required');

        return $val;
    }
}

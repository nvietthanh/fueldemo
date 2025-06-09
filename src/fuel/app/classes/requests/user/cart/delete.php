<?php

use Fuel\Core\Validation;

class Requests_User_Cart_Delete extends Requests_Common_Base
{
    public static function setValidator(Validation $val): Validation
    {
        $val->add('product_id', 'product id')->add_rule('required');

        return $val;
    }
}

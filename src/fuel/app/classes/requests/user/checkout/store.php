<?php

use Fuel\Core\Validation;

class Requests_User_Checkout_Store extends Requests_Common_Base
{
    public static function setValidator(Validation $val): Validation
    {
        $val->add('fullname', 'name')->add_rule('required');
        $val->add('address', 'address')->add_rule('required');
        $val->add('phone_number', 'phone number')->add_rule('required');
        $val->add('email', 'email')->add_rule('required');

        return $val;
    }
}

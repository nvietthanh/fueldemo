<?php

use Fuel\Core\Validation;
use Rules\PhoneRules;

class Requests_User_Checkout_Store extends Requests_Common_Base
{
    public static function setValidator(Validation $val): Validation
    {
        $val->add_callable(PhoneRules::forge('phone_rules'));

        $val->add('fullname', 'name')
            ->add_rule('required')
            ->add_rule('max_length', 255);
        $val->add('address', 'address')
            ->add_rule('required')
            ->add_rule('max_length', 255);
        $val->add('phone_number', 'phone number')
            ->add_rule('required')
            ->add_rule('min_length', 10)
            ->add_rule('max_length', 11)
            ->add_rule('valid_phone_number');

        return $val;
    }
}

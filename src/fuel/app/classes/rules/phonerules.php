<?php

namespace Rules;

use Fuel\Core\Validation;

class PhoneRules extends Validation
{
    public static function _validation_valid_phone_number($val)
    {
        return preg_match('/^0[0-9]{9,10}$/', $val) === 1;
    }
}

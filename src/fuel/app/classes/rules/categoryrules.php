<?php

namespace Rules;

use Fuel\Core\DB;
use Fuel\Core\Validation;

class CategoryRules extends Validation
{
    public static function _validation_category_exists($val)
    {
        return DB::select('id')
            ->from('categories')
            ->where('id', '=', $val)
            ->execute()
            ->count() > 0;
    }
}

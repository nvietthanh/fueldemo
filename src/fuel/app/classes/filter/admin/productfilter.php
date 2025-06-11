<?php

namespace Filter\Admin;

use Filter\BaseFilter;

class ProductFilter extends BaseFilter
{
    public static function apply($query, array $input)
    {
        self::filterName($query, $input);
        self::filterCategory($query, $input);

        return $query;
    }

    protected static function filterName($query, array $input)
    {
        $val = $input['name'] ?? null;

        if (self::isNotEmpty($val)) {
            $query->where('name', 'like', '%' . $val . '%');
        }
    }

    protected static function filterCategory($query, array $input)
    {
        $val = $input['category_id'] ?? null;

        if (self::isNotEmpty($val)) {
            $val = is_array($val) ? $val : [$val];

            $query->where('category_id', 'in', $val);
        }
    }
}

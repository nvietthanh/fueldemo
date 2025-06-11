<?php

namespace Filter;

abstract class BaseFilter
{
    abstract public static function apply($query, array $input);

    protected static function isNotEmpty($val = null): bool
    {
        return isset($val) && !empty($val) && $val !== '';
    }
}

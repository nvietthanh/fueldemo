<?php

namespace Traits;

trait Filterable
{
    public static function filters(string $filterClass, array $input = [])
    {
        $query = static::query();

        if (class_exists($filterClass) && method_exists($filterClass, 'apply')) {
            return $filterClass::apply($query, $input);
        }

        return $query;
    }
}

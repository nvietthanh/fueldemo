<?php

abstract class Model_Base extends \Orm\Model
{
    public static function find_or_fail(string $id)
    {
        $model = self::find($id);

        if (!$model) {
            throw new HttpNotFoundException();
        }

        return $model;
    }
}

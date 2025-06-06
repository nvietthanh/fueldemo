<?php

abstract class Model_Base extends \Orm\Model
{
    public static function findOrfail(string $id)
    {
        $model = self::find($id);

        if (!$model) {
            throw new HttpNotFoundException();
        }

        return $model;
    }
}

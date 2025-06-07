<?php

use Exception\ValidationException;
use Fuel\Core\Validation;
use Helpers\ValidationHelper;

abstract class Requests_Common_Base
{
    protected static $validation;

    public static function getValidator(): Validation
    {
        if (static::$validation === null) {
            static::$validation = Validation::forge();
        }

        return static::$validation;
    }

    public static function validate(array $input): array
    {
        $val = static::getValidator();

        $val = static::setValidator($val);

        if (!$val->run($input)) {
            $errors = ValidationHelper::getErrors($val);

            throw new ValidationException($errors);
        }

        return $val->validated();
    }

    abstract public static function setValidator(Validation $val): Validation;
}

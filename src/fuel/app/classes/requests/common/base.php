<?php

use Exception\ValidationException;
use Fuel\Core\Validation;
use Helpers\ValidationHelper;

abstract class Requests_Common_Base
{
    protected static $validation;

    public static function get_validator(): Validation
    {
        if (static::$validation === null) {
            static::$validation = Validation::forge();
        }

        return static::$validation;
    }

    public static function validate(array $input): array
    {
        $val = static::get_validator();

        $val = static::set_validator($val);

        if (!$val->run($input)) {
            $errors = ValidationHelper::get_errors($val);

            throw new ValidationException($errors);
        }

        return $val->validated();
    }

    abstract public static function set_validator(Validation $val): Validation;
}

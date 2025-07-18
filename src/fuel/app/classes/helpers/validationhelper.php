<?php

namespace Helpers;

use Fuel\Core\Validation;

class ValidationHelper
{
    /**
     * Collect multiple validation errors per field from a Validation instance.
     *
     * @param \Validation $validation
     * @return array
     */
    public static function get_errors(Validation $validation)
    {
        $errors = [];

        foreach ($validation->error() as $field => $error_obj) {
            $errors[$field][] = $error_obj->get_message();
        }

        return $errors;
    }
}

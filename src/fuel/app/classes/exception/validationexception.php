<?php

namespace Exception;

class ValidationException extends BaseException
{
    protected $status = 422;

    public function __construct(array $errors, string $message = 'Validation failed.')
    {
        parent::__construct($message, $this->status, $errors);
    }
}

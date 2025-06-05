<?php

namespace Exception;

class NotFoundException extends BaseException
{
    protected $status = 404;

    public function __construct(string $message = 'Not found.', array $data = [])
    {
        parent::__construct($message, $this->status, $data);
    }
}

<?php

namespace Exception;

abstract class BaseException extends \FuelException
{
    protected $status = 500;
    protected $data = [];

    public function __construct(string $message = 'Error', ?int $code = null, array $data = [])
    {
        parent::__construct($message, $code ?? $this->status);
        $this->data = $data;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getData()
    {
        return $this->data;
    }

    public function toJson()
    {
        return [
            'status' => $this->getStatus(),
            'message' => $this->getMessage(),
            'data' => $this->getData(),
        ];
    }
}

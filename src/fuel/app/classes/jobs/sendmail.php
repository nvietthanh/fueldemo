<?php

use Fuel\Core\Log;
use Traits\Dispatchable;

class Jobs_SendMail
{
    use Dispatchable;

    protected $data;
    protected $type;

    public function __construct(array $data, string $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function handle()
    {
        Log::error('send mail');
    }
}

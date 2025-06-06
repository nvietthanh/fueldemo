<?php

use Email\Email;
use Fuel\Core\Log;
use Traits\Dispatchable;

class Jobs_Admin_SendMail
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
        $email = Email::forge();
        $email->from('noreply@myapp.test', 'My App');
        $email->to('test@fake.com', 'Test User');
        $email->subject('Test Mail via Mailpit (Docker)');
        $email->body('Hello, đây là email test gửi từ FuelPHP qua Mailpit.');

        $email->send();
    }
}

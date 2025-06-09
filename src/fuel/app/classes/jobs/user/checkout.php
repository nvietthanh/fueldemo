<?php

use Email\Email;
use Fuel\Core\View;
use Traits\Dispatchable;

class Jobs_User_Checkout
{
    use Dispatchable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        $message = View::forge('mail/user/checkout/order-success', $this->data);
        $customer = $this->data['customer'];

        $email = Email::forge();
        $email->from('no-reply@yourdomain.com', 'Your Brand')
            ->to($customer['email'], $customer['fullname'])
            ->subject('Your Order Was Successful')
            ->html_body($message)
            ->send();
    }
}

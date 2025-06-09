<?php

use Email\Email;
use Fuel\Core\View;
use Traits\Dispatchable;

class Jobs_User_Checkout
{
    use Dispatchable;

    protected string $order_id;

    public function __construct(string $order_id)
    {
        $this->order_id = $order_id;
    }

    public function handle()
    {
        $order = Model_Order::query()
            ->related([
                'user' => [
                    'select' => ['id', 'email'],
                ],
                'orderProducts' => [
                    'select' => ['product_id', 'name', 'quantity', 'price'],
                ]
            ])
            ->where('id', $this->order_id)
            ->get_one();

        $customer = json_decode($order->customer, true);
        $user = $order->user;

        if ($user) {
            $message = View::forge('mail/user/checkout/order-success', [
                'customer' => $customer,
                'products' => $order->orderProducts,
            ]);

            $email = Email::forge();
            $email->to($user->email, $customer['fullname'])
                ->subject('Your Order Was Successful')
                ->html_body($message)
                ->send();
        }
    }
}

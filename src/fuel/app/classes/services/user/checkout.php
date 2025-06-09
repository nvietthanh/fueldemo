<?php

use Auth\Auth;
use Fuel\Core\DB;

class Services_User_Checkout
{
    private static $instance;

    public static function forge(): Services_User_Checkout
    {
        if (!isset(self::$instance)) {
            self::$instance = new Services_User_Checkout();
        }

        return self::$instance;
    }

    public function getCart()
    {
        $authUser = Auth::get_user_id();
        $userId = $authUser[1];

        $cartItems = DB::select(
            'carts.product_id',
            'carts.quantity',
            'products.name',
            'products.price',
            'products.image_path',
            'carts.updated_at',
            [DB::expr('carts.quantity * products.price'), 'total']
        )
            ->from('carts')
            ->join('products', 'LEFT')
            ->on('carts.product_id', '=', 'products.id')
            ->where('carts.user_id', '=', $userId)
            ->order_by('carts.updated_at', 'DESC')
            ->as_object()
            ->execute()
            ->as_array();

        return $cartItems;
    }

    public function createCheckout(array $data): void
    {
        $auth_user = Auth::get_user_id();
        $user_id = $auth_user[1];

        // create order and order product
        $this->createOrder($user_id, $data);

        // remove all product in cart
        $this->removeAllCart($user_id);
    }

    private function createOrder(string $user_id, array $data): void
    {
        $cartItems = DB::select('products.id', 'products.name', 'products.price', 'carts.quantity', 'carts.updated_at')
            ->from('carts')
            ->join('products', 'LEFT')
            ->on('carts.product_id', '=', 'products.id')
            ->where('carts.user_id', '=', $user_id)
            ->order_by('carts.updated_at', 'DESC')
            ->as_object()
            ->execute()
            ->as_array();

        $order = Model_Order::forge([
            'user_id' => $user_id,
            'customer' => json_encode($data),
        ]);
        $order->save();

        foreach ($cartItems as $item) {
            $order_product = Model_Order_Product::forge([
                'order_id'   => $order->id,
                'product_id' => $item->id,
                'name' => $item->name,
                'quantity'   => $item->quantity,
                'price'      => $item->price,
            ]);
            $order_product->save();

            $product = Model_Product::find($item->id);
            if ($product) {
                $product->quantity = max(0, $product->quantity - $item->quantity);
                $product->save();
            }
        }

        Jobs_User_Checkout::dispatch($order->id);
    }

    private function removeAllCart(string $user_id): void
    {
        $cartItems = Model_Cart::query()
            ->where('user_id', $user_id)
            ->get();

        foreach ($cartItems as $cartItem) {
            $cartItem->delete();
        }
    }
}

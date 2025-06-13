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

    public function get_cart()
    {
        $auth_user = Auth::get_user_id();
        $user_id = $auth_user[1];

        $cart_items = DB::select(
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
            ->where('carts.user_id', '=', $user_id)
            ->order_by('carts.updated_at', 'DESC')
            ->as_object()
            ->execute()
            ->as_array();

        return $cart_items;
    }

    public function create_checkout(array $data): void
    {
        $auth_user = Auth::get_user_id();
        $user_id = $auth_user[1];

        // create order and order product
        $this->create_order($user_id, $data);

        // remove all product in cart
        $this->remove_all_item_cart($user_id);
    }

    private function create_order(string $user_id, array $data): void
    {
        $cart_items = DB::select('products.id', 'products.name', 'products.price', 'carts.quantity', 'carts.updated_at')
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

        foreach ($cart_items as $cart_item) {
            $order_product = Model_Order_Product::forge([
                'order_id'   => $order->id,
                'product_id' => $cart_item->id,
                'name' => $cart_item->name,
                'quantity'   => $cart_item->quantity,
                'price'      => $cart_item->price,
            ]);
            $order_product->save();

            $product = Model_Product::find($cart_item->id);
            if ($product) {
                $product->quantity = max(0, $product->quantity - $cart_item->quantity);
                $product->save();
            }
        }

        Jobs_User_Checkout::dispatch($order->id);
    }

    private function remove_all_item_cart(string $user_id): void
    {
        $cart_items = Model_Cart::query()
            ->where('user_id', $user_id)
            ->get();

        foreach ($cart_items as $cartItem) {
            $cartItem->delete();
        }
    }
}

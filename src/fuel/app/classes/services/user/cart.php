<?php

use Auth\Auth;
use Fuel\Core\DB;

class Services_User_Cart
{
    private static $instance;

    public static function forge(): Services_User_Cart
    {
        if (!isset(self::$instance)) {
            self::$instance = new Services_User_Cart();
        }

        return self::$instance;
    }

    public function get_cart(): array
    {
        $auth_user = Auth::get_user_id();
        $user_id = $auth_user[1];

        $cart_items = DB::select('carts.product_id', 'carts.quantity', 'products.name', 'products.price', 'products.image_path', 'carts.updated_at')
            ->from('carts')
            ->join('products', 'LEFT')
            ->on('carts.product_id', '=', 'products.id')
            ->where('carts.user_id', '=', $user_id)
            ->order_by('carts.updated_at', 'DESC')
            ->order_by('carts.created_at', 'DESC')
            ->as_object()
            ->execute()
            ->as_array();

        return $cart_items;
    }

    public function store_cart(array $data): void
    {
        Model_Product::find_or_fail($data['product_id']);
        $auth_user = Auth::get_user_id();
        $user_id = $auth_user[1];

        $cart = Model_Cart::query()
            ->where('user_id', $user_id)
            ->where('product_id', $data['product_id'])
            ->get_one();

        if (is_null($cart)) {
            $data_update = array_merge($data, [
                'user_id' => $user_id,
            ]);

            $cart = Model_Cart::forge($data_update);
        } else {
            $data_store = array_merge($data, [
                'quantity' => $data['quantity'] + 1,
                'updated_at' => time(),
            ]);

            $cart->set($data_store);
        }

        $cart->save();
    }

    public function update_cart(array $data): void
    {
        Model_Product::find_or_fail($data['product_id']);
        $auth_user = Auth::get_user_id();
        $user_id = $auth_user[1];

        $cart = Model_Cart::query()
            ->where('user_id', $user_id)
            ->where('product_id', $data['product_id'])
            ->get_one();

        if ($cart) {
            $cart->set($data);
            $cart->save();
        }
    }

    public function remove_cart(array $data): void
    {
        $auth_user = Auth::get_user_id();
        $user_id = $auth_user[1];

        $cart = Model_Cart::query()
            ->where('user_id', $user_id)
            ->where('product_id', $data['product_id'])
            ->get_one();

        if ($cart) {
            $cart->delete();
        }
    }
}

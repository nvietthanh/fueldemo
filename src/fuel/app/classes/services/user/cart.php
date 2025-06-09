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

    public function getCart(): array
    {
        $authUser = Auth::get_user_id();
        $userId = $authUser[1];

        $cartItems = DB::select('carts.product_id', 'carts.quantity', 'products.name', 'products.price', 'products.image_path', 'carts.updated_at')
            ->from('carts')
            ->join('products', 'LEFT')
            ->on('carts.product_id', '=', 'products.id')
            ->where('carts.user_id', '=', $userId)
            ->order_by('carts.updated_at', 'DESC')
            ->order_by('carts.created_at', 'DESC')
            ->as_object()
            ->execute()
            ->as_array();

        return $cartItems;
    }

    public function storeCart(array $data): void
    {
        Model_Product::findOrfail($data['product_id']);
        $authUser = Auth::get_user_id();
        $userId = $authUser[1];

        $cart = Model_Cart::query()
            ->where('user_id', $userId)
            ->where('product_id', $data['product_id'])
            ->get_one();

        if (is_null($cart)) {
            $cart = Model_Cart::forge(array_merge($data, [
                'user_id' => $userId,
            ]));
        } else {
            $cart->set(array_merge($data, [
                'quantity' => $data['quantity'] + 1,
                'updated_at' => time(),
            ]));
        }

        $cart->save();
    }

    public function updateCart(array $data): void
    {
        Model_Product::findOrfail($data['product_id']);
        $authUser = Auth::get_user_id();
        $userId = $authUser[1];

        $cart = Model_Cart::query()
            ->where('user_id', $userId)
            ->where('product_id', $data['product_id'])
            ->get_one();

        if ($cart) {
            $cart->set($data);
            $cart->save();
        }
    }

    public function removeCart(array $data): void
    {
        $authUser = Auth::get_user_id();
        $userId = $authUser[1];

        $cart = Model_Cart::query()
            ->where('user_id', $userId)
            ->where('product_id', $data['product_id'])
            ->get_one();

        if ($cart) {
            $cart->delete();
        }
    }
}

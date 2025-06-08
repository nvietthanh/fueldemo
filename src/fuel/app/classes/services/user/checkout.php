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
        $total_price = array_sum(array_column($cartItems, 'total'));

        Jobs_User_Checkout::dispatch([
            'customer' => $data,
            'products' => $cartItems,
            'total_price' => $total_price,
        ]);
    }
}

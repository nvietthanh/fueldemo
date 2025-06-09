<?php

use Exception\ValidationException;
use Facades\Storage;
use Helpers\FileHelper;
use Helpers\UploadHelper;

class Services_Admin_Product
{
    private static $instance;

    public static function forge(): Services_Admin_Product
    {
        if (!isset(self::$instance)) {
            self::$instance = new Services_Admin_Product();
        }

        return self::$instance;
    }

    public function createProduct(array $data): Model_Product
    {
        $image_path = FileHelper::upload($data['image_file'], 'products');

        $store_data = [
            'name'        => $data['name'] ?? null,
            'price'       => $data['price'] ?? null,
            'quantity'    => $data['quantity'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'description' => $data['description'] ?? null,
            'image_path'  => $image_path,
        ];

        $product = Model_Product::forge($store_data);

        $product->save();

        return $product;
    }

    public function updateProduct(Model_Product $product, array $data): Model_Product
    {
        $update_data = [
            'name'        => $data['name'] ?? null,
            'price'       => $data['price'] ?? null,
            'quantity'    => $data['quantity'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'description' => $data['description'] ?? null,
        ];

        if (isset($data['image_file'])) {
            $image_path = FileHelper::upload($data['image_file'], 'products');

            $update_data['image_path'] = $image_path;
        }

        $product->set($update_data);
        $product->save();

        return $product;
    }
}

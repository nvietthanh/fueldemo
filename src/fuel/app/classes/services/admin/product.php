<?php

use Exception\ValidationException;
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
        $upload_data = UploadHelper::process_file();

        if (!$upload_data['success']) {
            throw new ValidationException($upload_data['errors']);
        }

        $store_data = [
            'name'        => $data['name'] ?? null,
            'price'       => $data['price'] ?? null,
            'quantity'    => $data['quantity'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'description' => $data['description'] ?? null,
            'image_path'       => $upload_data['paths']['image_file'],
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

        if (!empty($_FILES['image_file'])) {
            $upload_data = UploadHelper::process_file();

            if ($upload_data['success']) {
                $update_data['image_path'] = $upload_data['paths']['image_file'] ?? $product->image_path;
            } else {
                throw new ValidationException($upload_data['errors']);
            }
        }

        $product->set($update_data);
        $product->save();

        return $product;
    }
}

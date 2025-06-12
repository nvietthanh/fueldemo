<?php

class Services_Admin_Category
{
    private static $instance;

    public static function forge(): Services_Admin_Category
    {
        if (!isset(self::$instance)) {
            self::$instance = new Services_Admin_Category();
        }

        return self::$instance;
    }

    public function create_category(array $data): Model_Category
    {
        $store_data = [
            'name' => $data['name'],
        ];

        $category = Model_Category::forge($store_data);

        $category->save();

        return $category;
    }

    public function update_category(Model_Category $category, array $data): Model_Category
    {
        $update_data = [
            'name' => $data['name'],
        ];

        $category->set($update_data);
        $category->save();

        return $category;
    }
}

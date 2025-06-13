<?php

use Helpers\PaginationHelper;

class Controller_User_Products extends Controller_Base
{
	public $template = 'layouts/user';

	public function action_index()
	{
		$total = Model_Product::query()->count();

		$pagination = PaginationHelper::paginate($total, 16);

		$products = Model_Product::query()
			->select('id', 'name', 'image_path', 'price', 'quantity', 'category_id', 'created_at', 'updated_at')
			->related('category')
			->order_by('updated_at', 'DESC')
			->order_by('id', 'DESC')
			->rows_offset($pagination['offset'])
			->rows_limit($pagination['per_page'])
			->get();

		$data = [
			'products' => $products,
			'pagination' => $pagination,
		];

		$this->template->title = 'Product list';
		$this->template->content = View::forge('user/product/index', $data, false);
		$this->template->css = 'user/product.css';
		$this->template->js = 'user/product.js';
	}

	public function action_show(string $id)
	{
		$product = Model_Product::query()
			->related('category')
			->where('id', $id)
			->get_one();

		if (!$product) {
			return $this->json_response(null, 'Product not found', 404);
		}

		$data_response = $product->to_array();
		$data_response['image_url'] = get_file_url($product->image_path);

		return $this->json_response($data_response, 'Updated successfully!', 200);
	}
}

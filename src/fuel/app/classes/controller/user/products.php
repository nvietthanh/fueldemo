<?php

use Helpers\PaginationHelper;

class Controller_User_Products extends Controller_Base
{
	public $template = 'layouts/user';

	public function action_index()
	{
		$products = Model_Product::query()
			->select('id', 'name', 'image_path', 'price', 'quantity', 'category_id', 'created_at', 'updated_at')
			->related('category')
			->order_by('updated_at', 'DESC')
			->order_by('id', 'DESC')
			->rows_offset(Pagination::get('offset'))
			->rows_limit(Pagination::get('per_page'))
			->get();

		$data['products'] = $products;
		$data['pagination'] = PaginationHelper::paginate();

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
			return $this->jsonResponse(null, 'Product not found', 404);
		}

		return $this->jsonResponse($product->to_array(), 'Updated successfully!', 200);
	}
}

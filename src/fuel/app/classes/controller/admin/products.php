<?php

use Filter\Admin\ProductFilter;
use Fuel\Core\Input;
use Helpers\PaginationHelper;

class Controller_Admin_Products extends Controller_Admin_Common_Auth
{
	/**
	 * @var product_service Handles business logic for products
	 */
	protected $product_service;

	public $template = 'layouts/admin';

	public function before()
	{
		parent::before();

		$this->product_service = new Services_Admin_Product();
	}

	public function action_index()
	{
		$query = Model_Product::filters(ProductFilter::class, Input::get())
			->select('id', 'name', 'image_path', 'price', 'quantity', 'category_id', 'created_at', 'updated_at');

		$total = $query->count();
		$pagination = PaginationHelper::paginate($total, 10);

		$products = $query->related('category')
			->order_by('updated_at', 'DESC')
			->order_by('id', 'DESC')
			->rows_offset($pagination['offset'])
			->rows_limit($pagination['per_page'])
			->get();
		$categories = Model_Category::find('all');

		$data = [
			'categories' => $categories,
			'products' => $products,
			'pagination' => $pagination,
		];

		$this->template->active_menu = 'products';
		$this->template->title = 'Manage product';
		$this->template->content = View::forge('admin/products/index', $data, false);
		$this->template->js = 'admin/product/list.js';
	}

	public function action_create()
	{
		$data['categories'] = Model_Category::find('all');

		$this->template->active_menu = 'products';
		$this->template->title = 'Create product';
		$this->template->content = View::forge('admin/products/create', $data);
		$this->template->js = [
			'admin/form/form.js',
			'admin/product/create.js',
		];
	}

	public function action_store()
	{
		$post = Input::post();
		$file = Input::file();

		if (!empty($file['image_file']) && $file['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
			$post['image_file'] = $file['image_file'];
		}

		$post_val = Requests_Admin_Product_Store::validate($post);

		$this->product_service->create_product($post_val);

		return $this->json_response(null, 'Created successfully!', 200);
	}

	public function action_edit(string $id)
	{
		$product = Model_Product::find_or_fail($id);

		$data['product'] = $product;
		$data['categories'] = Model_Category::find('all');

		$this->template->active_menu = 'products';
		$this->template->title = 'Edit product';
		$this->template->content = View::forge('admin/products/edit', $data);
		$this->template->js = [
			'admin/form/form.js',
			'admin/product/edit.js',
		];
	}

	public function action_update(string $id)
	{
		$product = Model_Product::find_or_fail($id);

		$post = Input::post();
		$file = Input::file();

		if (!empty($file['image_file']) && $file['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
			$post['image_file'] = $file['image_file'];
		}

		$post_val = Requests_Admin_Product_Update::validate($post);

		$this->product_service->update_product($product, $post_val);

		return $this->json_response(null, 'Updated successfully!', 200);
	}

	public function action_delete(string $id)
	{
		$product = Model_Product::find_or_fail($id);

		$product->delete();

		return $this->json_response(null, 'Deleted successfully');
	}
}

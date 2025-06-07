<?php

use Fuel\Core\Input;
use Helpers\PaginationHelper;

class Controller_Admin_Products extends Controller_Admin_Base_Auth
{
	protected $productService;

	public $template = 'layouts/admin';

	public function before()
	{
		parent::before();

		$this->productService = Services_Admin_Product::forge();
	}

	public function action_index()
	{
		$total = Model_Product::query()->count();
		$products = Model_Product::query()
			->select('id', 'name', 'image_path', 'price', 'quantity', 'category_id', 'created_at', 'updated_at')
			->related('category')
			->rows_offset(Pagination::get('offset'))
			->rows_limit(Pagination::get('per_page'))
			->get();

		$data['products'] = $products;
		$data['pagination'] = PaginationHelper::paginate($total);

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
		$input = Input::post();

		if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
			$input['image_file'] = $_FILES['image_file'];
		}

		$inputVal = Requests_Admin_Product_Store::validate($input);

		$this->productService->createProduct($inputVal);

		return $this->jsonResponse(null, 'Created successfully!', 200);
	}

	public function action_edit(string $id)
	{
		$product = Model_Product::findOrFail($id);

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
		$product = Model_Product::findOrFail($id);

		$input = Input::post();

		if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
			$input['image_file'] = $_FILES['image_file'];
		}

		$inputVal = Requests_Admin_Product_Update::validate($input);

		$this->productService->updateProduct($product, $inputVal);

		return $this->jsonResponse(null, 'Updated successfully!', 200);
	}

	public function action_delete(string $id)
	{
		$product = Model_Product::findOrfail($id);

		$product->delete();

		return $this->jsonResponse(null, 'Deleted successfully');
	}
}

<?php

use Exception\ValidationException;
use Fuel\Core\Input;

class Controller_Admin_Products extends Controller_Admin_Base_Auth
{
	public $template = 'layouts/admin';

	public function action_index()
	{
		Jobs_SendMail::dispatch([
			'email' => 'abc@example.com',
			'subject' => 'Test',
			'body' => 'Hello world!',
		], 'test');

		$total = Model_Product::query()->count();
		$products = Model_Product::query()
			->select('id', 'name', 'image_path', 'price', 'quantity', 'category_id', 'created_at', 'updated_at')
			->related('category')
			->rows_offset(Pagination::get('offset'))
			->rows_limit(Pagination::get('per_page'))
			->get();

		$data['products'] = $products;
		$data['pagination'] = Helpers_Pagination::paginate($total, 'admin/products');

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

		$val = Validation::forge();

		$val->add('name', 'name')->add_rule('required');
		$val->add('price', 'price')->add_rule('required');
		$val->add('quantity', 'quantity')->add_rule('required');
		$val->add('category_id', 'category id')->add_rule('required');
		$val->add('description', 'description')->add_rule('required');
		$val->add('image_file', 'Image')
			->add_rule('required');

		if (!$val->run($input)) {
			$errors = Helpers_Validation::getErrors($val);

			throw new ValidationException($errors);
		}

		$upload_data = Helpers_Upload::process_file();

		if ($upload_data['success']) {
			$store_data = [
				'name'        => $val->validated('name'),
				'price'       => $val->validated('price'),
				'quantity'    => $val->validated('quantity'),
				'category_id' => $val->validated('category_id'),
				'description' => $val->validated('description'),
				'image_path'       => $upload_data['paths']['image_file'],
			];

			$product = Model_Product::forge($store_data);

			$product->save();

			return $this->jsonResponse(null, 'Created successfully!', 200);
		} else {
			return $this->jsonResponse($upload_data['errors'], 'Validation failed', 422);
		}
	}

	public function action_edit(string $id)
	{
		$product = Model_Product::find($id);

		if (!$product) {
			throw new HttpNotFoundException();
		}

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
		$product = Model_Product::find($id);

		if (!$product) {
			return $this->jsonResponse(null, 'Not found', 404);
		}

		$input = Input::post();

		if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
			$input['image_file'] = $_FILES['image_file'];
		}

		$val = Validation::forge();

		$val->add('name', 'name')->add_rule('required');
		$val->add('price', 'price')->add_rule('required');
		$val->add('quantity', 'quantity')->add_rule('required');
		$val->add('category_id', 'category id')->add_rule('required');
		$val->add('description', 'description')->add_rule('required');
		$val->add('image_file', 'image');

		if (!$val->run($input)) {
			$errors = Helpers_Validation::getErrors($val);

			throw new ValidationException($errors);
		}

		$update_data = [
			'name'        => $val->validated('name'),
			'price'       => $val->validated('price'),
			'quantity'    => $val->validated('quantity'),
			'category_id' => $val->validated('category_id'),
			'description' => $val->validated('description'),
		];

		if (!empty($_FILES['image_file'])) {
			$upload_data = Helpers_Upload::process_file();

			if ($upload_data['success']) {
				$update_data['image_path'] = $upload_data['paths']['image_file'] ?? $product->image_path;
			} else {
				return $this->jsonResponse($upload_data['errors'], 'Validation failed', 422);
			}
		}

		$product->set($update_data);
		$product->save();

		return $this->jsonResponse(null, 'Updated successfully!', 200);
	}

	public function action_delete(string $id)
	{
		$product = Model_Product::find($id);

		if (!$product) {
			return $this->jsonResponse(null, 'Not found', 404);
		}

		try {
			$product->delete();

			return $this->jsonResponse(null, 'Deleted successfully');
		} catch (\Exception $e) {
			return $this->jsonResponse(null, 'Failed to delete product', 500);
		}
	}
}

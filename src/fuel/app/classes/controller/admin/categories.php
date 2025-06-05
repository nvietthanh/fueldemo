<?php

use Exception\NotFoundException;
use Exception\ValidationException;

class Controller_Admin_Categories extends Controller_Admin_Base_Auth
{
	public $template = 'layouts/admin';

	public function action_index()
	{
		$total = Model_Category::query()->count();
		$categories = Model_Category::query()
			->select('id', 'name', 'created_at', 'updated_at')
			->rows_offset(Pagination::get('offset'))
			->rows_limit(Pagination::get('per_page'))
			->get();

		$data['categories'] = $categories;
		$data['pagination'] = Helpers_Pagination::paginate($total, 'admin/categories');

		$this->template->active_menu = 'categories';
		$this->template->title = 'Manage Category';
		$this->template->content = View::forge('admin/categories/index', $data, false);
		$this->template->js = [
			'admin/form/form.js',
			'admin/categories/list.js',
		];
	}

	public function action_store()
	{
		$input = Input::post();

		$val = Validation::forge();

		$val->add('name', 'name')->add_rule('required');

		if (!$val->run($input)) {
			$errors = Helpers_Validation::getErrors($val);

			throw new ValidationException($errors);
		}

		$product = Model_Category::forge([
			'name' => $val->validated('name'),
		]);

		$product->save();

		return $this->jsonResponse(null, 'Created successfully!', 200);
	}

	public function action_show(string $id)
	{
		$category = Model_Category::find($id);

		if (!$category) {
			return $this->jsonResponse(null, 'Product not found', 404);
		}

		return $this->jsonResponse($category->to_array());
	}

	public function action_update(string $id)
	{
		$category = Model_Category::find($id);

		if (!$category) {
			return $this->jsonResponse(null, 'Product not found', 404);
		}

		$input = Input::post();

		$val = Validation::forge();

		$val->add('name', 'name')->add_rule('required');

		if (!$val->run($input)) {
			$errors = Helpers_Validation::getErrors($val);

			throw new ValidationException($errors);
		}

		$category->set([
			'name' => $val->validated('name')
		]);
		$category->save();

		return $this->jsonResponse(null, 'Updated successfully!', 200);
	}

	public function action_delete(string $id)
	{
		$category = Model_Category::query()
			->related('products')
			->where('id', $id)
			->get_one();

		if (!$category) {
			return $this->jsonResponse(null, 'Not found', 404);
		}
		if (count($category->products)) {
			return $this->jsonResponse(null, 'Forbidden', 403);
		}

		$category->delete();

		return $this->jsonResponse(null, 'Deleted successfully');
	}
}

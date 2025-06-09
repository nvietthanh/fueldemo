<?php

use Helpers\PaginationHelper;

class Controller_Admin_Categories extends Controller_Admin_Common_Auth
{
	protected $categoryService;

	public $template = 'layouts/admin';

	public function before()
	{
		parent::before();

		$this->categoryService = Services_Admin_Category::forge();
	}

	public function action_index()
	{
		$total = Model_Category::query()->count();

		$pagination = PaginationHelper::paginate($total, 10);

		$categories = Model_Category::query()
			->select('id', 'name', 'created_at', 'updated_at')
			->rows_offset($pagination['offset'])
			->rows_limit($pagination['per_page'])
			->get();

		$data = [
			'categories' => $categories,
			'pagination' => $pagination,
		];

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

		$inputVal = Requests_Admin_Category_Store::validate($input);

		$this->categoryService->createCategory($inputVal);

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
		$category = Model_Category::findOrfail($id);

		$input = Input::post();

		$inputVal = Requests_Admin_Category_Store::validate($input);

		$this->categoryService->updateCategory($category, $inputVal);

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

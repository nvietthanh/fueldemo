<?php

use Fuel\Core\DB;

class Controller_User_Checkout extends Controller_User_Common_Auth
{
	/**
	 * @var checkout_service Handles business logic for checkouts
	 */
	protected $checkout_service;

	public $template = 'layouts/user';

	public function before()
	{
		parent::before();

		$this->checkout_service = Services_User_Checkout::forge();
	}

	public function action_index()
	{
		$cart_items = $this->checkout_service->get_cart();

		$data = [
			'cart_items' => $cart_items,	
		];

		$this->template->title = 'Checkout';
		$this->template->content = View::forge('user/checkout/index', $data, false);
		$this->template->css = [
			'admin/form.css',
			'user/checkout.css',
		];
		$this->template->js = [
			'admin/form/form.js',
			'user/checkout.js',
		];
	}

	public function action_store()
	{
		$post = Input::post();

		$post_val = Requests_User_Checkout_Store::validate($post);

		DB::start_transaction();
		try {
			$this->checkout_service->create_checkout($post_val);

			DB::commit_transaction();

			return $this->json_response(null, 'Created successfully!', 200);
		} catch (\Throwable $th) {
			DB::rollback_transaction();

			return $this->json_response([], $th->getMessage(), 500);
		}
	}

	public function action_complete()
	{
		$this->template->title = 'Order success';
		$this->template->content = View::forge('user/checkout/complete');
		$this->template->css = 'user/checkout.css';
		$this->template->js = [
			'admin/form/form.js',
			'user/checkout.js',
		];
	}
}

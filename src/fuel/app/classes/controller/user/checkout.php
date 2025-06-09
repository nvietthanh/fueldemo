<?php

use Fuel\Core\DB;

class Controller_User_Checkout extends Controller_User_Common_Auth
{
	protected $checkoutService;

	public $template = 'layouts/user';

	public function before()
	{
		parent::before();

		$this->checkoutService = Services_User_Checkout::forge();
	}

	public function action_index()
	{
		$cartItems = $this->checkoutService->getCart();

		$data['cartItems'] = $cartItems;

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
		$input = Input::post();

		$inputVal = Requests_User_Checkout_Store::validate($input);

		DB::start_transaction();
		try {
			$this->checkoutService->createCheckout($inputVal);
			echo($agag);

			DB::commit_transaction();


			return $this->jsonResponse(null, 'Created successfully!', 200);
		} catch (\Throwable $th) {
			DB::rollback_transaction();

			return $this->jsonResponse([], $th->getMessage(), 500);
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

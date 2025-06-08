<?php

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
		$this->template->css = 'user/checkout.css';
		$this->template->js = [
			'admin/form/form.js',
			'user/checkout.js',
		];
	}

	public function action_store()
	{
		$input = Input::post();

		$inputVal = Requests_User_Checkout_Store::validate($input);

		$this->checkoutService->createCheckout($inputVal);

		return $this->jsonResponse(null, 'Created successfully!', 200);
	}
}

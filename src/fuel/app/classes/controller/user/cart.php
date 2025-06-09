<?php

class Controller_User_Cart extends Controller_User_Common_Auth
{
	protected $cartService;

	public $template = 'layouts/user';

	public function before()
	{
		parent::before();

		$this->cartService = Services_User_Cart::forge();
	}

	public function action_index()
	{
		$cartItems = $this->cartService->getCart();

		$data['cartItems'] = $cartItems;

		$this->template->title = 'Cart';
		$this->template->content = View::forge('user/cart/index', $data, false);
		$this->template->css = 'user/cart.css';
		$this->template->js = [
			'user/cart.js',
		];
	}

	public function action_store()
	{
		$input = Input::post();

		$inputVal = Requests_User_Cart_Store::validate($input);

		$this->cartService->storeCart($inputVal);

		return $this->jsonResponse(null);
	}

	public function action_update()
	{
		$input = Input::post();

		$inputVal = Requests_User_Cart_Update::validate($input);

		$this->cartService->updateCart($inputVal);

		return $this->jsonResponse(null, 'Update cart successfully!', 200);
	}

	public function action_delete()
	{
		$input = Input::delete();

		$inputVal = Requests_User_Cart_Delete::validate($input);

		$this->cartService->removeCart($inputVal);

		return $this->jsonResponse(null, 'Deleted successfully!', 200);
	}
}

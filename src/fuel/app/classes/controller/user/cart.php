<?php

class Controller_User_Cart extends Controller_User_Common_Auth
{
	/**
	 * @var cart_service Handles business logic for carts
	 */
	protected $cart_service;

	public $template = 'layouts/user';

	public function before()
	{
		parent::before();

		$this->cart_service = new Services_User_Cart();
	}

	public function action_index()
	{
		$cart_items = $this->cart_service->get_cart();

		$data = [
			'cart_items' => $cart_items,
		];

		$this->template->title = 'Cart';
		$this->template->content = View::forge('user/cart/index', $data, false);
		$this->template->css = 'user/cart.css';
		$this->template->js = [
			'user/cart.js',
		];
	}

	public function action_store()
	{
		$post = Input::post();

		$post_val = Requests_User_Cart_Store::validate($post);

		$this->cart_service->store_cart($post_val);

		return $this->json_response(null);
	}

	public function action_update()
	{
		$post = Input::post();

		$post_val = Requests_User_Cart_Update::validate($post);

		$this->cart_service->update_cart($post_val);

		return $this->json_response(null, 'Update cart successfully!', 200);
	}

	public function action_delete()
	{
		$delete = Input::delete();

		$delete_val = Requests_User_Cart_Delete::validate($delete);

		$this->cart_service->remove_cart($delete_val);

		return $this->json_response(null, 'Deleted successfully!', 200);
	}
}

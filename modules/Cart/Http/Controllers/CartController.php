<?php namespace Modules\Cart\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class CartController extends Controller {
	
	public function index()
	{
		return view('cart::index');
	}
	
}
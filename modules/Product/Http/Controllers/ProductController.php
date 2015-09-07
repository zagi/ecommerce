<?php namespace Modules\Product\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class ProductController extends Controller {
	
	public function index()
	{
		return view('product::index');
	}
	
}
<?php namespace Modules\Category\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class CategoryController extends Controller {
	
	public function index()
	{
		return view('category::index');
	}
	
}
<?php namespace Modules\Manufacturer\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class ManufacturerController extends Controller {
	
	public function index()
	{
		return view('manufacturer::index');
	}
	
}
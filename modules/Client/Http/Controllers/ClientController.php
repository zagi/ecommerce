<?php namespace Modules\Client\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class ClientController extends Controller {
	
	public function index()
	{
		return view('client::index');
	}
	
}
<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Libs\Http\Request;

class AboutController extends Controller
{
	/**
	 * /about GET
	 */
	public function index(Request $request, $id)
	{
		$this->render('pages/home');
	}
}
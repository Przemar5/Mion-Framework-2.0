<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Libs\Http\Request;
use App\Entities\User;
use App\Repositories\UserRepository;

class HomeController extends Controller
{
	/**
	 * / GET
	 */
	public function index(Request $request)
	{
		$this->render('pages/home');
	}
}
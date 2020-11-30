<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Entities\User;
use App\Entities\Tokens\RegistrationToken;
use App\Repositories\UserRepository;
use App\Forms\RegistrationForm;
use App\Forms\Factories\RegistrationFormFactory;
use Libs\Http\Request;
use Libs\Routing\Router;
use Libs\Hash;

class RegistrationController extends Controller
{
	/**
	 * /register GET
	 */
	public function renderPage(Request $request)
	{
		$form = RegistrationFormFactory::create();

		$this->render('auth/register', [
			'form' => $form->getView(),
		]);
	}

	/** 
	 * /register POST 
	 */
	public function register(Request $request)
	{
		$token = RegistrationToken::getFromSession();
		$form = RegistrationFormFactory::create();

		if ($token->sessionDataMatchesRequest($request)) {
			$this->processRequest($form);
		}

		$this->render('auth/register', [
			'form' => $form->getView(),
		]);
	}

	private function processRequest(RegistrationForm $form)
	{
		$form->validate();

		if ($form->isValid()) {
			$user = $this->createUser($form);
			$this->saveUser($user);
			$this->sendActiovationEmail($user);
			$this->redirectAfterRegistration();
		}
	}

	private function createUser(RegistrationForm $form): User
	{
		$user = new User();
		$user->email = $form->getValueOf('email');
		$user->username = $form->getValueOf('username');
		$user->password = Hash::make($form->getValueOf('password'));
		$user->setCreatedAt();

		return $user;
	}

	private function saveUser(User $user)
	{
		$userRepo = new UserRepository();
		$userRepo->saveEntity($user);
	}

	private function sendActiovationEmail(User $user)
	{
		//
	}

	private function redirectAfterRegistration()
	{
		$router = new Router();
		$router->loadRoutes('./config/routes.json');
		$router->redirectTo('login_page');
	}
}
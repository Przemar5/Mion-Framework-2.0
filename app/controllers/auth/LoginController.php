<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Entities\User;
use App\Entities\Tokens\LoginToken;
use App\Repositories\UserRepository;
use App\Forms\LoginForm;
use App\Forms\Factories\LoginFormFactory;
use Libs\Http\Request;
use Libs\Routing\Router;
use Libs\Storage\Session;
use Libs\Hash;

class LoginController extends Controller
{
	/**
	 * /login GET
	 */
	public function renderPage(Request $request)
	{
		$form = LoginFormFactory::create();

		$this->render('auth/login', [
			'form' => $form->getView(),
		]);
	}

	/**
	 * /login POST
	 */
	public function login(Request $request)
	{
		$token = LoginToken::getFromSession();
		$form = LoginFormFactory::create();

		if ($token->sessionDataMatchesRequest($request)) {
			$this->handleForm($form);
		}
		
		$this->render('auth/login', [
			'form' => $form->getView(),
		]);
	}

	private function handleForm(LoginForm $form)
	{
		$form->validate();

		if ($form->isValid()) {
			$user = $this->findUser($form);

			if (empty($user)) {
				return;
			}

			$this->loginUser($user);
			$this->redirectToHomePage();
		}
	}

	private function findUser(LoginForm $form)
	{
		$email = $form->getValueOf('email');
		$userRepo = new UserRepository();
		$user = $userRepo->getVerifiedUserByEmail($email);
		$plainPassword = $form->getValueOf('password');

		if (empty($user)) {
			return;
		}

		if (!Hash::verify($plainPassword, $user->password)) {
			return;
		}

		return $user;
	}

	private function loginUser(User $user): void
	{
		Session::set('user_id', $user->id);
	}

	private function redirectToHomePage()
	{
		$router = new Router();
		$router->loadRoutes('./config/routes.json');
		$router->redirectTo('home');
	}

	/**
	 * /logout GET
	 */
	public function logout(Request $request)
	{
		Session::unset('user_id');
		$this->redirectToHomePage();
	}
}
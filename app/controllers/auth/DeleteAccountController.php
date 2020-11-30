<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Entities\User;
use App\Entities\Tokens\DeleteAccountToken;
use App\Repositories\UserRepository;
use App\Forms\DeleteAccountForm;
use App\Forms\Factories\DeleteAccountFormFactory;
use Libs\Http\Request;
use Libs\Routing\Router;
use Libs\Storage\Session;

class DeleteAccountController extends Controller
{
	/**
	 * /delete-account GET
	 */
	public function renderPage(Request $request)
	{
		$form = DeleteAccountFormFactory::create();

		$this->render('auth/account_delete', [
			'form' => $form->getView(),
		]);
	}

	/**
	 * /delete-account Post
	 */
	public function delete(Request $request)
	{
		$token = DeleteAccountToken::getFromSession();
		$form = DeleteAccountFormFactory::create();

		if ($token->sessionDataMatchesRequest($request)) {
			$this->handleForm($form);
		}

		$this->render('auth/account_delete', [
			'form' => $form->getView(),
		]);
	}

	private function handleForm(DeleteAccountForm $form): void
	{
		$form->validate();

		if ($form->isValid()) {
			$user = Security::loggedUser();

			$this->softDeleteUser();
			$this->logout();
		}
	}

	private function deleteUser(User $user): void
	{
		$userRepo = new UserRepository();
		$userRepo->delete($user);
	}

	private function logout(): void
	{
		Session::unset('user_id');
	}
}
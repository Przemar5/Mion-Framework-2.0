<?php

namespace App\Forms\Factories;

use App\Entities\Tokens\DeleteAccountToken;
use App\Forms\Factories\FormFactory;
use App\Forms\Form;
use App\Forms\DeleteAccountForm;
use App\Forms\Views\DeleteAccountFormView;
use App\Forms\Validators\DeleteAccountFormValidator;
use Libs\Validators\Strategies\ForEachFieldStopOnFirstFailure;

class DeleteAccountFormFactory implements FormFactory
{
	public static function create(): Form
	{
		$view = new DeleteAccountFormView();
		$validator = self::createValidator();
		$form = new DeleteAccountForm($view, $validator);
		$token = self::getNewToken();
		$token->storeInSession();
		$form->setToken($token);

		return new DeleteAccountForm($view, $validator);
	}

	private static function createValidator(): DeleteAccountFormValidator
	{
		$strategy = new ForEachFieldStopOnFirstFailure();

		return new DeleteAccountFormValidator([], $strategy);
	}

	private static function getNewToken(): DeleteAccountToken
	{
		$token = new DeleteAccountToken();
		$token->name = 'account_delete_token';
		$token->setValueToRandomHexString();

		return $token;
	}
}
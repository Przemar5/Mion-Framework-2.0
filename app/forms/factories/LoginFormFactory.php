<?php

namespace App\Forms\Factories;

use App\Entities\Tokens\LoginToken;
use App\Forms\Factories\FormFactory;
use App\Forms\Form;
use App\Forms\LoginForm;
use App\Forms\Views\LoginFormView;
use App\Forms\Validators\LoginFormValidator;
use Libs\Validators\Strategies\ForEachFieldStopOnFirstFailure;

class LoginFormFactory implements FormFactory
{
	public static function create(): Form
	{
		$view = new LoginFormView();
		$validator = self::createValidator();
		$form = new LoginForm($view, $validator);
		$token = self::getNewToken();
		$token->storeInSession();
		$form->setToken($token);

		return new LoginForm($view, $validator);
	}

	private static function createValidator(): LoginFormValidator
	{
		$strategy = new ForEachFieldStopOnFirstFailure();

		return new LoginFormValidator([], $strategy);
	}

	private static function getNewToken(): LoginToken
	{
		$token = new LoginToken();
		$token->name = 'login_token';
		$token->setValueToRandomHexString();

		return $token;
	}
}
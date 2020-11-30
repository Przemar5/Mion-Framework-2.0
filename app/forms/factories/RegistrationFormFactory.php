<?php

namespace App\Forms\Factories;

use App\Entities\Tokens\RegistrationToken;
use App\Forms\Factories\FormFactory;
use App\Forms\Form;
use App\Forms\RegistrationForm;
use App\Forms\Views\RegistrationFormView;
use App\Forms\Validators\RegistrationFormValidator;
use Libs\Validators\Strategies\ForEachFieldStopOnFirstFailure;

class RegistrationFormFactory implements FormFactory
{
	public static function create(): Form
	{
		$view = new RegistrationFormView();
		$validator = self::createValidator();
		$form = new RegistrationForm($view, $validator);
		$token = self::getNewToken();
		$token->storeInSession();
		$form->setToken($token);

		return $form;
	}

	private static function createValidator(): RegistrationFormValidator
	{
		$strategy = new ForEachFieldStopOnFirstFailure();

		return new RegistrationFormValidator([], $strategy);
	}

	private static function getNewToken(): RegistrationToken
	{
		$token = new RegistrationToken();
		$token->name = 'registration_token';
		$token->setValueToRandomHexString();

		return $token;
	}
}
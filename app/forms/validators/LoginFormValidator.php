<?php

namespace App\Forms\Validators;

use App\Forms\Validators\FormValidator;
use Libs\Validators\Strategies\ValidationStrategy;
use Libs\Validators\RequiredValidator;
use Libs\Validators\MinValidator;
use Libs\Validators\MaxValidator;
use Libs\Validators\EmailValidator;
use Libs\Validators\PatternMatchValidator;

class LoginFormValidator extends FormValidator
{
	public function __construct(?array $formData = [], ValidationStrategy $strategy)
	{
		$this->formData = $formData;
		$validators = $this->getAllValidators();

		parent::__construct($validators, $strategy);
	}

	private function getAllValidators(): array
	{
		return [
			'email' => $this->getEmailValidators(),
			'password' => $this->getPasswordValidators(),
		];
	}

	private function getEmailValidators(): array
	{
		return [
			new RequiredValidator('Email is required.'),
			new MinValidator('Email must be at least 7 characters long.', 7),
			new MaxValidator('Email must be shorter than 256 characters long.', 255),
			new EmailValidator('Email must be a proper email address.'),
		];
	}

	private function getPasswordValidators(): array
	{
		return [
			new RequiredValidator('Password is required.'),
			new MinValidator('Password must be at least 8 characters long.', 8),
			new MaxValidator('Password must be shorter than 256 characters long.', 255),
			new PatternMatchValidator('Password must contain a lower case letter, '.
				'an uppercase letter, a number and a special character.', 
				'/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,10}$/ui'),
		];
	}

	private function getRepeatedPasswordFromRequestOrEmptyString(): string
	{
		return $_REQUEST['repeat_password'] ?? '';
	}
}
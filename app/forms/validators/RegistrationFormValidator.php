<?php

namespace App\Forms\Validators;

use App\Forms\Validators\FormValidator;
use Libs\Validators\Strategies\ValidationStrategy;
use Libs\Validators\RequiredValidator;
use Libs\Validators\MinValidator;
use Libs\Validators\MaxValidator;
use Libs\Validators\EmailValidator;
use Libs\Validators\PatternMatchValidator;
use Libs\Validators\BothMatchValidator;
use Libs\Validators\UniqueValidator;
use App\Repositories\UserRepository;

class RegistrationFormValidator extends FormValidator
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
			'username' => $this->getUsernameValidators(),
			'password' => $this->getPasswordValidators(),
			'repeat_password' => [],
		];
	}

	private function getEmailValidators(): array
	{
		return [
			new RequiredValidator('Email is required.'),
			new MinValidator('Email must be at least 7 characters long.', 7),
			new MaxValidator('Email must be shorter than 256 characters long.', 255),
			new EmailValidator('Email must be a proper email address.'),
			new UniqueValidator('Email is already taken. Please choose another one.', new UserRepository(), 'email'),
		];
	}

	private function getUsernameValidators(): array
	{
		return [
			new RequiredValidator('Username is required.'),
			new MinValidator('Username must be at least 3 characters long.', 3),
			new MaxValidator('Username must be shorter than 256 characters long.', 255),
			new PatternMatchValidator('Username contains forbidden characters.', 
				'/^[\w\+\-\.\s]+$/ui'),
			new UniqueValidator('Username is already taken. Please choose another one.', new UserRepository(), 'username'),
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
			new BothMatchValidator('Both passwords must be identical.', 
				$this->getRepeatedPasswordFromRequestOrEmptyString()),
		];
	}

	private function getRepeatedPasswordFromRequestOrEmptyString(): string
	{
		return $_REQUEST['repeat_password'] ?? '';
	}
}
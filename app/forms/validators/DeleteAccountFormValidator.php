<?php

namespace App\Forms\Validators;

use App\Forms\Validators\FormValidator;
use Libs\Validators\Strategies\ValidationStrategy;

class DeleteAccountFormValidator extends FormValidator
{
	public function __construct(?array $formData = [], ValidationStrategy $strategy)
	{
		$this->formData = $formData;
		$validators = $this->getAllValidators();

		parent::__construct($validators, $strategy);
	}

	private function getAllValidators(): array
	{
		return [];
	}
}
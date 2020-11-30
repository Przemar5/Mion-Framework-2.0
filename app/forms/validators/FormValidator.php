<?php

namespace App\Forms\Validators;

use Libs\Validators\Strategies\ValidationStrategy;

abstract class FormValidator
{
	public array $formData = [];
	public array $validators = [];
	protected array $errors = [];
	protected ValidationStrategy $strategy;


	public function __construct(array $validators, ValidationStrategy $strategy)
	{
		$this->validators = $validators;
		$this->strategy = $strategy;
	}

	public function validate(): void
	{
		if (!empty($this->formData)) {
			$this->errors = $this->strategy->runAndGetErrors(
				$this->formData, 
				$this->validators
			);
		}
	}

	public function isValid(): bool
	{
		$validated = array_map(fn($e) => empty($e), $this->errors);

		return array_reduce($validated, fn($a, $b) => $a && $b, true);
	}

	public function getErrors(): array
	{
		return $this->errors;
	}
}
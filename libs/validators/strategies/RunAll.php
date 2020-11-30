<?php

namespace Libs\Validators\Strategies;

use Libs\Validators\Strategies\ValidationStrategy;

class RunAll implements ValidationStrategy
{
	public function runAndGetErrors(array $formData, array $validators): array
	{
		$errors = [];
		
		foreach ($formData as $key => $value) {
			$errors[$key] = $this->getFailureMessages($value, $validators[$key]);
		}

		return $errors;
	}

	public function getFailureMessages($value, array $validators): array
	{
		$errors = [];

		foreach ($validators as $validator) {
			if (!$validator->validate($value)) {
				$errors[] = $validator->getMessage();
			}
		}

		return $errors;
	}
}
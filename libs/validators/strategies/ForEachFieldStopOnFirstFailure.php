<?php

namespace Libs\Validators\Strategies;

use Libs\Validators\Strategies\ValidationStrategy;

class ForEachFieldStopOnFirstFailure implements ValidationStrategy
{
	public function runAndGetErrors(array $formData, array $validators): array
	{
		$errors = [];

		foreach ($formData as $key => $value) {
			$errors[$key] = 
				$this->getFirstFailureMessageIfFailed($value, $validators[$key]) ?? [];
		}

		return $errors;
	}

	public function getFirstFailureMessageIfFailed($value, array $validators)
	{
		foreach ($validators as $validator) {
			if (!$validator->validate($value)) {
				return [$validator->getMessage()];
			}
		}
	}
}
<?php

namespace Libs\Validators;

use Libs\Validators\Validator;

class RequiredValidator extends Validator
{
	public function __construct(string $message)
	{
		$this->message = $message;
	}

	public function validate($value): bool
	{
		$this->throwExceptionIfNotString($value);

		return (!empty($value));
	}
}
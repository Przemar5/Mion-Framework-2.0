<?php

namespace Libs\Validators;

use Libs\Validators\Validator;

class MinValidator extends Validator
{
	protected int $min;


	public function __construct(string $message, int $min)
	{
		$this->message = $message;
		$this->min = $min;
	}

	public function validate($value): bool
	{
		$this->throwExceptionIfNotString($value);

		return (is_string($value) && (strlen($value) >= $this->min));
	}
}
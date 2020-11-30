<?php

namespace Libs\Validators;

use Libs\Validators\Validator;

class MaxValidator extends Validator
{
	protected int $max;


	public function __construct(string $message, int $max)
	{
		$this->message = $message;
		$this->max = $max;
	}

	public function validate($value): bool
	{
		$this->throwExceptionIfNotString($value);

		return (is_string($value) && (strlen($value) <= $this->max));
	}
}
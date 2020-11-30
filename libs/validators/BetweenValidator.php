<?php

namespace Libs\Validators;

use Libs\Validators\Validator;

class MinValidator extends Validator
{
	protected int $min;
	protected int $max;


	public function __construct(string $message, int $min, int $max)
	{
		$this->message = $message;
		$this->min = $min;
		$this->max = $max;
	}

	public function validate($value): bool
	{
		$this->throwExceptionIfNotString($value);

		return (is_string($value) && 
			(strlen($value) >= $this->min) && (strlen($value) <= $this->max));
	}
}
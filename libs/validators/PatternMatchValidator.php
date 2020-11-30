<?php

namespace Libs\Validators;

use Libs\Validators\Validator;

class PatternMatchValidator extends Validator
{
	protected string $regex;


	public function __construct(string $message, string $regex)
	{
		$this->message = $message;
		$this->regex = $regex;
	}

	public function validate($value): bool
	{
		$this->throwExceptionIfNotString($value);

		return ((bool) preg_match($this->regex, $value));
	}
}
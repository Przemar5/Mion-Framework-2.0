<?php

namespace Libs\Validators;

use Libs\Validators\Validator;

class BothMatchValidator extends Validator
{
	protected string $stringToMatch;


	public function __construct(string $message, string $stringToMatch)
	{
		$this->message = $message;
		$this->stringToMatch = $stringToMatch;
	}

	public function validate($value): bool
	{
		$this->throwExceptionIfNotString($value);

		return ($this->stringToMatch === $value);
	}
}
<?php

namespace Libs\Validators;

use Libs\Exceptions\InvalidTypeException;

abstract class Validator
{
	protected string $message;


	abstract public function validate($value): bool;

	public function getMessage(): ?string
	{
		return $this->message;
	}

	protected function throwExceptionIfNotString($value): void
	{
		if (!is_string($value)) {
			$type = gettype($value);

			throw new InvalidTypeException("Given value must be of type 'string', given '$type'.");
		}
	}
}
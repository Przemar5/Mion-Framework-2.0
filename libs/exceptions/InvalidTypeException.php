<?php

namespace Libs\Exceptions;

class InvalidTypeException extends \Exception
{
	public function __construct($message = null, $code = 0, ?\Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
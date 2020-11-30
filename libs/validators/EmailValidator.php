<?php

namespace Libs\Validators;

use Libs\Validators\PatternMatchValidator;

class EmailValidator extends PatternMatchValidator
{
	public function __construct(string $message)
	{
		// Regex test: https://regex101.com/r/d673Se/1
		parent::__construct($message, 
			'/^([\w\.\-\+\!\%]{1,64}|(\".*\"){1,64})@[\w\-]{1,64}(\.\w{2,20})?$/ui');
	}
}
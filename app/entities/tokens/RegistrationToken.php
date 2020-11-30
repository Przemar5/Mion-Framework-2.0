<?php

namespace App\Entities\Tokens;

use App\Entities\Token;

class RegistrationToken extends Token
{
	protected array $attributeSessionVarMap = [
		'value' => 'registration_token',
	];
}
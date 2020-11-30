<?php

namespace App\Entities\Tokens;

use App\Entities\Token;

class LoginToken extends Token
{
	protected array $attributeSessionVarMap = [
		'value' => 'login_token',
	];
}
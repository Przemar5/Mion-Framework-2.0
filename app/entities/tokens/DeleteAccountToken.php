<?php

namespace App\Entities\Tokens;

use App\Entities\Token;

class DeleteAccountToken extends Token
{
	protected array $attributeSessionVarMap = [
		'value' => 'account_delete_token',
	];
}
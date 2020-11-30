<?php

namespace App\Entities\Factories;

use App\Entities\Token;

class RegistrationTokenFactory implements EntityFactory
{
	public static function create(): Token
	{
		return new Token();
	}
}
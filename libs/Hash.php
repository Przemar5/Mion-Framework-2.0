<?php

namespace Libs;

class Hash
{
	public static function make(string $value)
	{
		return password_hash($value, PASSWORD_DEFAULT);
	}

	public static function verify(string $password, string $hashed): bool
	{
		return password_verify($password, $hashed);
	}
}
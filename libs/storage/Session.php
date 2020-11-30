<?php

namespace Libs\Storage;

class Session
{
	public static function start(): void
	{
		session_start();
		session_regenerate_id();
	}

	public static function get(string $key)
	{
		if (self::isset($key)) {
			return $_SESSION[$key];
		}
	}

	public static function set(string $key, $value): void
	{
		$_SESSION[$key] = $value;
	}

	public static function isset(string $key): bool
	{
		return isset($_SESSION[$key]);
	}

	public static function unset(string $key): void
	{
		if (self::isset($key)) {
			unset($_SESSION[$key]);
		}
	}

	public static function dump(): void
	{
		var_dump($_SESSION);
	}

	public static function end(): void 
	{
		session_destroy();
	}
}
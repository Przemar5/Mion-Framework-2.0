<?php

namespace Libs\Routing;

use Libs\JsonParser;

abstract class AbstractRouter
{
	abstract public function route();

	public static function shortenedCurrentUri(): string
	{
		return $_SERVER['PATH_INFO'] ?? '/';
	}

	public static function currentUri(): string
	{
		return sprintf("%s://%s%s", 
			$_SERVER['REQUEST_SCHEME'],
			$_SERVER['HTTP_HOST'],
			$_SERVER['REQUEST_URI']
		);
	}

	public static function baseUri(): string
	{
		$end = strlen($_SERVER['REQUEST_URI']) - strlen($_SERVER['PATH_INFO'] ?? '');
		$baseAfterHost = substr($_SERVER['REQUEST_URI'], 0, $end);

		return sprintf("%s://%s%s", 
			$_SERVER['REQUEST_SCHEME'],
			$_SERVER['HTTP_HOST'],
			$baseAfterHost
		);
	}
}
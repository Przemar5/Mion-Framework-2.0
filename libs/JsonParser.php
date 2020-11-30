<?php

namespace Libs;

class JsonParser
{
	public static function parseFile(string $path)
	{
		if (is_readable($path)) {
			$json = file_get_contents($path);
			return json_decode($json, true);
		}
	}
}
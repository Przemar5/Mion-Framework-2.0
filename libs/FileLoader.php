<?php

namespace Libs;

class FileLoader
{
	public function load(string $path)
	{
		if (is_readable($path)) {
			require $path;
		}
	}

	public function getFileContent(string $path)
	{
		if (is_readable($path)) {
			return file_get_contents($path);
		}
	}
}
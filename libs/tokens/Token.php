<?php

namespace Libs\Tokens;

use Libs\Storage\Session;

class Token
{
	protected string $name;
	protected string $value;


	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function generate(): void
	{
		$bytes = random_bytes(32);
		$this->value = bin2hex($bytes);
	}

	public function storeInSession(): void
	{
		Session::set($this->name, $this->value);
	}

	public function populateFromSession(): void
	{
		if (Session::exists($this->name)) {
			$this->value = Session::get($this->name);
		}
	}

	public function check(): bool
	{
		
	}
}
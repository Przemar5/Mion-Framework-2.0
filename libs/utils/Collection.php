<?php

namespace Libs\Utils;

class Collection
{
	public array $values;

	public function __construct(array $values)
	{
		$this->values = $values;
	}
}
<?php

namespace Libs;

use Libs\Database\Database;
use App\Entities\Entity;

class EntityManager
{
	protected Database $database;

	public function __construct(Database $database)
	{
		$this->database = $database;
	}
}
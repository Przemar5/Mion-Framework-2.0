<?php

namespace Libs;

use Libs\EntityManager;
use Libs\Database\DatabaseFactory;

class EntityManagerFactory
{
	public static function create(): EntityManager
	{
		$database = DatabaseFactory::createForConfiguration('./config/database.json');

		return new EntityManager($database);
	}
}
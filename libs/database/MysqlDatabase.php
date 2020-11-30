<?php

namespace Libs\Database;

use Libs\Database\Database;

class MysqlDatabase extends Database
{
	public function __construct(
		string $dbHost, 
		string $dbPort, 
		string $dbName, 
		string $dbUser, 
		string $dbPassword)
	{
		parent::__construct('mysql', $dbHost, $dbPort, $dbName, $dbUser, $dbPassword);
	}
}
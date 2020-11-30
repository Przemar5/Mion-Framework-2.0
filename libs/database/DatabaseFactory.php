<?php

namespace Libs\Database;

use Libs\Database\Database;
use Libs\JsonParser;

class DatabaseFactory
{
	private static $dbConfig;


	public static function createForConfiguration(string $configFile)
	{
		self::loadDatabaseConfig($configFile);
		$databaseClassName = self::getDatabaseClassNameByDriver(self::$dbConfig['dbType']);
		
		try {
			$database = new $databaseClassName(
				self::$dbConfig['dbHost'],
				self::$dbConfig['dbPort'],
				self::$dbConfig['dbName'],
				self::$dbConfig['dbUser'],
				self::$dbConfig['dbPassword']
			);
			$database->queryBuilder = 
				self::createQueryBuilderForDatabaseDriver(self::$dbConfig['dbType']);

			return $database;
		}
		catch (\PDOException $e) {
			die($e->getMessage());
		}
	}

	private static function loadDatabaseConfig(string $configFile)
	{
		self::$dbConfig = JsonParser::parseFile($configFile);
	}

	private static function getDatabaseClassNameByDriver(string $type)
	{
		switch ($type) {
			case 'mysql': return \Libs\Database\MysqlDatabase::class;
			case 'pgsql': return \Libs\Database\PgsqlDatabase::class;
		}
	}

	private static function createQueryBuilderForDatabaseDriver(string $type)
	{
		switch ($type) {
			case 'mysql': return new \Libs\Database\Queries\MysqlQueryBuilder();
			case 'pgsql': return new \Libs\Database\Queries\PgsqlQueryBuilder();
		}
	}
}
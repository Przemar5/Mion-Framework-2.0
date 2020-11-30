<?php

namespace Libs\Database;

use Libs\Database\Queries\QueryBuilder;

abstract class Database
{
	public QueryBuilder $queryBuilder;
	protected \PDO $pdo;
	protected \PDOStatement $statement;
	protected $result;


	public function __construct(
		string $dbType,
		string $dbHost,
		string $dbPort,
		string $dbName,
		string $dbUser,
		string $dbPassword
	)
	{
		$dsn = "$dbType:host=$dbHost;port=$dbPort;dbname=$dbName";
		$this->pdo = new \PDO($dsn, $dbUser, $dbPassword);
    	$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public function executeWithBindings(array $params)
	{
		try {
			$this->statement = $this->pdo->prepare($this->queryBuilder->query);
			$this->statement->execute($params);
		}
		catch (\PDOException $e) {
			die($e->getMessage());
		}
	}

	public function fetchObject(string $class)
	{
		$this->result = $this->statement->fetchObject($class);
	}

	public function fetchAll(int $fetchType, string $class = null)
	{
		$this->result = $this->statement->fetchAll(
			\PDO::ATTR_FETCH_MODE_DEFAULT,
			$fetchType,
			$class
		);
	}

	public function fetchAllObjects(string $class)
	{
		$this->result = $this->statement->fetchAll(
			\PDO::ATTR_FETCH_MODE_DEFAULT,
			\PDO::FETCH_CLASS,
			$class
		);
	}

	public function fetchNum()
	{
		$this->result = $this->statement->fetch(\PDO::FETCH_NUM);
	}

	public function bindParams(array $params)
	{
		foreach ($params as $key => $value) {
			$type = $this->getPDOParamType($value);
			$this->statement->bindParam($key, $value, $type);
		}
	}

	protected function getPDOParamType($value): int
	{
		switch (gettype($value)) {
			case 'integer': return \PDO::PARAM_INT;
			case 'string': return \PDO::PARAM_STR;
			case 'boolean': return \PDO::PARAM_BOOL;
			case 'NULL': return \PDO::PARAM_INT;
		}
	}

	public function getResult()
	{
		if (!empty($this->result)) {
			return $this->result;
		}
	}
}
<?php

namespace Libs\Database\Queries;

use Libs\Database\Queries\QueryBuilder;

class PgsqlQueryBuilder extends QueryBuilder
{
	public function select(array $columns): self
	{
		$this->initializeQuery('SELECT');
		$columns = implode(', ', $columns);

		return $this->appendAndReturnSelf('SELECT '.$columns);
	}

	public function selectCount(string $column): self
	{
		$this->initializeQuery('SELECT');

		return $this->appendAndReturnSelf("SELECT COUNT($column)");
	}

	public function insertInto(string $table, array $columns): self
	{
		$this->initializeQuery('INSERT');
		$columns = $this->getListedValuesInBraces($columns);

		return $this->appendAndReturnSelf('INSERT INTO "'.$table.'" '.$columns);
	}

	public function update(string $table, array $columns): self
	{
		$this->initializeQuery('UPDATE');
		$columns = ' SET '.$this->stringifyMultitpleAssignmentsToBind($columns);

		return $this->appendAndReturnSelf("UPDATE \"$table\" $columns");
	}

	public function delete(): self
	{
		$this->initializeQuery('DELETE');

		return $this->appendAndReturnSelf('DELETE');
	}

	protected function initializeQuery(string $queryType): void
	{
		$this->clear();

		if ($this->checkIfProperQueryType($queryType)) {
			$this->queryType = $queryType;
		}
	}

	protected function checkIfProperQueryType(string $queryType): bool
	{
		$properTypes = ['SELECT', 'INSERT', 'UPDATE', 'DELETE'];
		
		return (in_array($queryType, $properTypes));
	}

	protected function getListedValuesInBraces(array $values): string
	{
		return '('.implode(', ', $values).')';
	}

	public function updateValues(array $values): self
	{
		$keys = array_keys($values);
		$values = array_map(
			fn($key) => $key.' = '.$values[$key], 
			$values
		);
		$values = ' VALUES ('.implode(', ', $values).')';

		return $this->appendAndReturnSelf($values);
	}

	public function updateValuesToBind(array $columns): self
	{
		$columns = $this->stringifyMultitpleAssignmentsToBind($columns);

		return $this->appendAndReturnSelf(' SET '.$columns);
	}

	public function insertValuesToBind(array $columns): self
	{
		$columns = $this->stringifyAsBindingKeys($columns);

		return $this->appendAndReturnSelf(' VALUES ('.$columns.')');
	}

	protected function stringifyMultitpleAssignmentsToBind(array $columns): string
	{
		$columns = array_map(fn($col) => "$col = :$col", $columns);

		return implode(', ', $columns);
	}

	protected function stringifyAsBindingKeys(array $columns): string
	{
		$columns = array_map(fn($col) => ":$col", $columns);

		return implode(', ', $columns);
	}

	public function from(string $table): self
	{
		return $this->appendAndReturnSelf(" FROM \"$table\"");
	}

	public function set(array $values): self
	{
		$keys = array_keys($values);
		$values = array_map(
			fn($key) => $key.' = '.$values[$key], 
			$values
		);
		$values = implode(', ', $values);

		return $this->appendAndReturnSelf(' SET '.$values);
	}

	public function where(string $condition): self
	{
		return $this->appendAndReturnSelf(' WHERE '.$condition);
	}

	public function andWhere(string $condition): self
	{
		return $this->appendAndReturnSelf(' AND '.$condition);
	}

	public function multipleWhere(array $conditions): self
	{
		$conditionString = ' WHERE '.implode(' AND ', $conditions);

		return $this->appendAndReturnSelf($conditionString);
	}

	public function limit($limit): self
	{
		return $this->appendAndReturnSelf(' LIMIT '.$limit);
	}

	public function offset($offset): self
	{
		return $this->appendAndReturnSelf(' OFFSET '.$offset);
	}

	public function innerJoin(string $table): self
	{
		return $this->appendAndReturnSelf(' INNER JOIN '.$table);
	}

	public function outerJoin(string $table): self
	{
		return $this->appendAndReturnSelf(' OUTER JOIN '.$table);
	}

	public function fullOuterJoin(string $table): self
	{
		return $this->appendAndReturnSelf(' FULL OUTER JOIN '.$table);
	}

	public function leftJoin(string $table): self
	{
		return $this->appendAndReturnSelf(' LEFT JOIN '.$table);
	}

	public function rightJoin(string $table): self
	{
		return $this->appendAndReturnSelf(' RIGHT JOIN '.$table);
	}

	public function on(string $join): self
	{
		return $this->appendAndReturnSelf(' ON '.$join);
	}
}
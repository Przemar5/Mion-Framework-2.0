<?php

namespace Libs\Database\Queries;

abstract class QueryBuilder
{
	public string $query;
	protected string $where;
	protected array $bindings = [];
	protected ?string $queryType;


	public function __toString()
	{
		return $this->query;
	}

	abstract public function select(array $columns): self;
	abstract public function selectCount(string $column): self;
	abstract public function insertInto(string $table, array $columns): self;
	abstract public function update(string $table, array $columns): self;
	abstract public function delete(): self;
	abstract public function insertValuesToBind(array $values): self;
	abstract public function updateValues(array $values): self;
	abstract public function from(string $table): self;
	abstract public function set(array $values): self;
	abstract public function where(string $condition): self;
	abstract public function andWhere(string $condition): self;
	// abstract public function orWhere(string $condition): self;
	abstract public function limit(mixed $limit): self;
	abstract public function offset(mixed $offset): self;
	abstract public function innerJoin(string $table): self;
	abstract public function outerJoin(string $table): self;
	abstract public function fullOuterJoin(string $table): self;
	abstract public function leftJoin(string $table): self;
	abstract public function rightJoin(string $table): self;
	abstract public function on(string $join): self;

	public function clear(): self
	{
		$this->query = '';
		$this->queryType = null;

		return $this;
	}

	public function getQueryType(): ?string
	{
		return $this->queryType;
	}

	protected function appendAndReturnSelf(string $string): self
	{
		$this->query .= $string;

		return $this;
	}
}
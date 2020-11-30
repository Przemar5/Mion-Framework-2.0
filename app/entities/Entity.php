<?php

namespace App\Entities;

use Libs\Database\Database;
use Libs\Database\DatabaseFactory;

abstract class Entity
{
	protected static string $table;
	protected static string $primaryKey;
	protected static ?string $softDeleteColumn = null;
	protected array $attributeColumnMap = [];
	protected array $uniqueColumns = [];


	public static function getPrimaryKey(): string
	{
		return static::$primaryKey;
	}

	public static function getTable(): string
	{
		return static::$table;
	}

	public static function getSoftDeleteColumn(): ?string
	{
		return static::$softDeleteColumn;
	} 

	protected function existsInDatabase(): bool
	{
		$primaryKey = $this->{static::$primaryKey} ?? null;

		return (!empty($primaryKey));
	}

	public function getColumns(): array
	{
		$primaryKey = static::$primaryKey;
		$columns = array_values($this->attributeColumnMap);

		return array_filter($columns, fn($col) => $primaryKey !== $col);
	}

	public function getValuesToSave(): array
	{
		return $this->getColumnPropertyMap($map);
	}

	public function getColumnPropertyMap(): array
	{
		$result = [];
		foreach ($this->attributeColumnMap as $property => $column) {
			$result[$column] = $this->{$property};
		}

		return $result;
	}

	public function getBindings(): array
	{
		$bindings = [];
		foreach ($this->attributeColumnMap as $property => $column) {
			if ($this->isNotPrimaryKey($property)) {
				$bindings[":$column"] = $this->{$property} ?? null;
			}
		}

		return $bindings;
	}

	public function getBindingsWithPrimaryKey(): array
	{
		$bindings = [];
		foreach ($this->attributeColumnMap as $property => $column) {
			$bindings[":$column"] = $this->{$property} ?? null;
		}

		return $bindings;
	}

	protected function isNotPrimaryKey(string $property): bool
	{
		return $property !== static::$primaryKey;
	}
}
<?php

namespace App\Repositories;

use App\Entities\Entity;
use Libs\Database\Database;
use Libs\Database\DatabaseFactory;

abstract class EntityRepository
{
	protected Database $database;
	protected string $caredEntityClass;


	public function __construct(string $caredEntityClass)
	{
		$this->caredEntityClass = $caredEntityClass;
		$this->loadDatabase();
	}

	private function loadDatabase(): void
	{
		$this->database = DatabaseFactory::createForConfiguration('./config/database.json');
	}

	public function findById($id)
	{
		$table = $this->getEntityTable();
		$primaryKey = $this->getEntotyPrimaryKey();

		$this->database->queryBuilder
			->select(['*'])
			->from($table)
			->where("$primaryKey = :$primaryKey");
		$this->appendSoftDeleteConditionAfterPreviousIfEnabled();

		$this->database->executeWithBindings([":$primaryKey" => (int) $id]);
		$this->database->fetchObject($this->caredEntityClass);

		return $this->database->getResult();
	}

	protected function getEntityTable(): string
	{
		return $this->caredEntityClass::getTable();
	}

	protected function getEntotyPrimaryKey(): string
	{
		return $this->caredEntityClass::getPrimaryKey();
	}

	public function findOne($criteria)
	{
		$table = $this->getEntityTable();
		$conditions = $this->extractConditions($criteria);
		$bindings = $this->extractBindings($criteria);

		$this->database->queryBuilder
			->select(['*'])
			->from($table)
			->multipleWhere($conditions);
		$this->appendSoftDeleteConditionAfterPreviousIfEnabled();
		$this->database->queryBuilder
			->limit(1);

		$this->database->executeWithBindings($bindings);
		$this->database->fetchObject($this->caredEntityClass);

		return $this->database->getResult();
	}

	protected function appendSoftDeleteConditionIfEnabled(): void
	{
		if ($this->entityIsSoftDeleteable()) {
			$condition = $this->getSoftDeleteCondition();
			$this->database->queryBuilder
				->where($condition);
		}
	}

	protected function appendSoftDeleteConditionAfterPreviousIfEnabled(): void
	{
		if ($this->entityIsSoftDeleteable()) {
			$condition = $this->getSoftDeleteCondition();
			$this->database->queryBuilder
				->andWhere($condition);
		}
	}

	protected function getEntitySoftDeleteColumn(): ?string
	{
		return $this->caredEntityClass::getSoftDeleteColumn();
	}

	protected function entityIsSoftDeleteable(): bool
	{
		return !empty($this->getEntitySoftDeleteColumn());
	}

	protected function getSoftDeleteCondition(): string
	{
		return $this->getEntitySoftDeleteColumn().' IS NULL';
	}

	public function findMany($criteria)
	{
		$table = $this->getEntityTable();
		$conditions = $this->extractConditions($criteria);
		$bindings = $this->extractBindings($criteria);

		$this->database->queryBuilder
			->select(['*'])
			->from($table)
			->multipleWhere($conditions);
		$this->appendSoftDeleteConditionAfterPreviousIfEnabled();

		$this->database->executeWithBindings($bindings);
		$this->database->fetchAllObjects($this->caredEntityClass);

		return $this->database->getResult();
	}

	protected function extractConditions(array $criteria)
	{
		$keys = array_keys($criteria);

		return array_map(
			fn($key) => "$key = :$key",
			$keys
		);
	}

	protected function extractBindings(array $criteria)
	{
		$bindings = [];
		foreach ($criteria as $key => $value) {
			$bindings[":$key"] = $value;
		}
		
		return $bindings;
	}

	public function saveEntity(Entity $entity)
	{
		if ($this->entityHasId($entity)) {
			$this->updateEntity($entity);
		}
		else {
			$this->insertEntity($entity);
		}
	}

	protected function entityHasId(Entity $entity): bool
	{
		$primaryKey = $this->getEntotyPrimaryKey();
		$table = $this->getEntityTable();

		return !empty($entity->{$primaryKey});
	}

	public function insertEntity(Entity $entity): void
	{
		$table = $this->getEntityTable();
		$columns = $entity->getColumns();
		$bindings = $entity->getBindings();

		$this->database->queryBuilder
			->insertInto($table, $columns)
			->insertValuesToBind($columns);

		$this->database->executeWithBindings($bindings);
	}

	public function updateEntity(Entity $entity): void
	{
		$table = $this->getEntityTable();
		$primaryKey = $this->getEntotyPrimaryKey();
		$columns = $entity->getColumns();
		$bindings = $entity->getBindingsWithPrimaryKey();

		$this->database->queryBuilder
			->update($table, $columns)
			->where("$primaryKey = :$primaryKey");
		
		// var_dump($bindings);
		// die;

		$this->database->executeWithBindings($bindings);
	}

	public function deleteEntity(Entity $entity): void
	{
		$table = $this->getEntityTable();
		$primaryKey = $this->getEntityPrimaryKey();
		$id = $entity->{$primaryKey};

		$this->database->queryBuilder
			->delete()
			->from($table)
			->where("$primaryKey = :$primaryKey");
		$this->database->executeWithBindings([":$primaryKey" => $id]);
	}

	public function softDeleteEntity(Entity $entity): void
	{
		$entity->setDeletedAt();
		$this->updateEntity($entity);
	}

	public function hasUniqueColumnValue(string $column, $value): bool
	{
		$table = $this->getEntityTable();
		$condition = "$column = :$column";
		$binding = [":$column" => $value];

		$this->database->queryBuilder
			->selectCount($column)
			->from($table)
			->where($condition);
		$this->appendSoftDeleteConditionAfterPreviousIfEnabled();
		$this->database->queryBuilder
			->limit(1);

		$this->database->executeWithBindings($binding);
		$this->database->fetchNum();

		return empty($this->database->getResult()[0]);
	}
}
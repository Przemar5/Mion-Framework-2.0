<?php

namespace App\Repositories;

use App\Repositories\EntityRepository;
use App\Entities\User;
use Libs\Hash;

class UserRepository extends EntityRepository
{
	public function __construct()
	{
		parent::__construct(User::class);
	}

	public function checkIfUsernameAndEmailUnique(User $user): bool
	{
		$columns = $user->getColumns();
		$bindings = $user->getBindings();

		$this->database->queryBuilder
			->insertInto($this->table, $columns)
			->insertValuesToBind($columns);
		$this->database->executeWithBindings($bindings);
	}

	public function getVerifiedUserByEmail(string $email): ?User
	{
		$table = $this->getEntityTable();
		$conditions = [
			'email = :email',
			'verified IS NOT NULL',
		];
		$bindings = [
			':email' => $email,
		];

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
}
<?php

namespace Libs\Validators;

use Libs\Validators\Validator;
use App\Repositories\EntityRepository;

class UniqueValidator extends Validator
{
	protected array $restValues;
	protected EntityRepository $repository;


	public function __construct(string $message, EntityRepository $repository, string $column)
	{
		$this->message = $message;
		$this->repository = $repository;
		$this->column = $column;
	}

	public function validate($value): bool
	{
		$this->throwExceptionIfNotString($value);

		return (is_string($value) && 
			$this->repository->hasUniqueColumnValue($this->column, $value));
	}
}
<?php

namespace App\Entities;

use App\Entities\Entity;

class User extends Entity
{
	protected static string $table = 'user';
	protected static string $primaryKey = 'id';
	protected static ?string $softDeleteColumn = 'deleted_at';
	public ?int $id;
	public ?string $email;
	public ?string $username;
	public ?string $password;
	public ?string $verified;
	public ?string $created_at;
	public ?string $updated_at;
	public ?string $deleted_at;

	protected array $attributeColumnMap = [
		'id' => 'id',
		'email' => 'email',
		'username' => 'username',
		'password' => 'password',
		'verified' => 'verified',
		'created_at' => 'created_at',
		'updated_at' => 'updated_at',
		'deleted_at' => 'deleted_at',
	];

	protected array $uniqueProperties = [
		'email', 'username',
	];


	public function setCreatedAt(): void
	{
		$this->created_at = $this->getFormattedCurrentDate();
	}

	public function setUpdatedAt(): void
	{
		$this->updated_at = $this->getFormattedCurrentDate();
	}

	public function setDeletedAt(): void
	{
		$this->deleted_at = $this->getFormattedCurrentDate();
	}

	protected function getFormattedCurrentDate(): string
	{
		$date = new \DateTime();

		return $date->format('Y-m-d H:i:s');
	}
}
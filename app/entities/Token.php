<?php

namespace App\Entities;

use App\Entities\Entity;
use App\Entities\User;
use Libs\Storage\Session;
use Libs\Http\Request;

class Token extends Entity
{
	public string $id;
	public string $name;
	public string $value;
	public string $role;
	public int $user_id;
	public string $created_at;
	public string $expires_at;
	public string $lifetime;
	protected array $attributeSessionVarMap = [];
	protected array $attributeColumnMap = [
		'id' => 'id',
		'name' => 'name',
		'value' => 'value',
		'role' => 'role',
		'user_id' => 'user_id',
		'created_at' => 'created_at',
		'expires_at' => 'expires_at',
	];


	public static function getFromSession(): self
	{
		$token = new static();
		$token->assignSessionDataToAttributes();
		$token->unsetDataStoredInSession();

		return $token;
	}

	protected function assignSessionDataToAttributes(): void
	{
		foreach ($this->attributeSessionVarMap as $attr => $sessionVar) {
			if (Session::isset($sessionVar)) {
				$this->{$attr} = Session::get($sessionVar);
			}
		}
	}

	protected function assignSessionVariableToAttribute(
		string $varName, 
		string $attrName
	): void
	{
		$this->{$attrName} = Session::get($varName);
	}

	public function unsetDataStoredInSession(): void
	{
		foreach ($this->attributeSessionVarMap as $sessionVar) {
			if (Session::isset($sessionVar)) {
				Session::unset($sessionVar);
			}
		}
	}

	public function storeInSession(): void
	{
		foreach ($this->attributeSessionVarMap as $attr => $sessionVar) {
			Session::set($sessionVar, $this->{$attr});
		}
	}

	public function sessionDataMatchesRequest(Request $request): bool
	{
		if (empty($this->value)) {
			return false;
		}
		$key = $this->attributeSessionVarMap['value'];

		return $this->value === $request->data[$key];
	}

	public function setValueToRandomHexString(): void
	{
		$bytes = random_bytes(32);
		$this->value = bin2hex($bytes);
	}

	public function setUser(User $user): void
	{
		$primaryKey = User::getPrimaryKey();
		$this->user_id = $user->{$primaryKey};
	}

	public function setCreatedAt(): void
	{
		$this->created_at = $this->getFormattedCurrentDate();
	}

	public function setExpiresAt(?string $delay = null): void
	{
		$delay = $delay ?? $this->lifetime;
		$this->expires_at = $this->getFormattedCurrentDate('+'.$delay);
	}

	protected function getFormattedCurrentDate(?string $delay = null): string
	{
		$date = new \DateTime($delay);

		return $date->format('Y-m-d H:i:s');
	}
}
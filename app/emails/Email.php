<?php

namespace App\Emails;

use App\Entities\User;

abstract class Email
{
	protected User $receiver;


	abstract public function send();

	public function setReceiver(User $user): void
	{
		$this->receiver = $user;
	}
}
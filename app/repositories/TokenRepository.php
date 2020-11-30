<?php

namespace App\Repositories;

use App\Repositories\EntityRepository;
use App\Entities\Token;

class TokenRepository extends EntityRepository
{
	public function __construct()
	{
		parent::__construct(Token::class);
	}
}
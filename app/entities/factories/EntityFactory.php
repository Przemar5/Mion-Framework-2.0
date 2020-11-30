<?php

namespace App\Entities\Factories;

use App\Entities\Entity;

interface EntityFactory
{
	public static function create(): Entity;
}
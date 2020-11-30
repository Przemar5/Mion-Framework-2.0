<?php

namespace App\Forms\Factories;

use App\Forms\Form;

interface FormFactory
{
	public static function create(): Form;
}
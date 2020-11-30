<?php

namespace Libs\Validators\Strategies;

interface ValidationStrategy
{
	public function runAndGetErrors(array $formData, array $validators): array;
}
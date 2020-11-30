<?php

namespace Database\Migrations;

abstract class Migration
{
	abstract function execute(): void;
	abstract function rollback(): void;
}
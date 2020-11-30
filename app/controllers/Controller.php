<?php

namespace App\Controllers;

use Libs\View;

class Controller
{
	protected View $view;

	public function __construct()
	{
		$this->view = new View();
	}

	protected function render(string $path, ?array $args = [])
	{
		$this->view->renderTemplate($path, $args);
		$this->view->loadLayout();
		// $this->view->insertData($args);
	}
}
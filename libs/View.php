<?php

namespace Libs;

class View
{
	private string $viewsFolder = './templates/';
	private string $viewFileExtension = 'php';
	public string $layoutFile = 'layouts/app';
	public string $pageTitle = 'Web Dev Blog';
	public array $sections = [];
	protected string $outputBuffer;

	public function loadLayout(): void
	{
		$path = $this->fullFilename($this->layoutFile);

		if (is_readable($path)) {
			require_once $path;
		}
	}

	public function renderTemplate(string $path, ?array $args = []): void
	{
		$path = $this->fullFilename($path);

		if (is_readable($path)) {
			extract($args);
			require_once $path;
		}
	}

	private function fullFilename(string $filepath): string
	{
		return $this->viewsFolder.$filepath.'.'.$this->viewFileExtension;
	}
	
	public function startSection(string $section): void
	{
		$this->outputBuffer = $section;
		ob_start();
	}
	
	public function endSection(): void
	{
		$this->sections[$this->outputBuffer] = ob_get_clean();
	}

	public function getAsset(string $path): ?string
	{
		$path = './public/'.$path;

		if (is_readable($path)) {
			return $path;
		}
	}
}
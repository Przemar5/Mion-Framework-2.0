<?php

namespace App\Forms\Views;

class FormView
{
	public array $formData = [];
	public array $errors = [];

	protected array $templates = [
		'form' => '<form $attrs>$content</form>',
		'form_start' => '<form $attrs>',
		'form_end' => '</form>',
		'label' => '<label $attrs>$text $input</label>',
		'input' => '<input $attrs>',
		'hidden' => '<input type="hidden" $attrs>',
		'checkbox' => '<input type="checkbox" $attrs>',
		'select' => '<select $attrs>$options</select>',
		'option' => '<option $attrs>',
		'radio' => '<input type="radio" $attrs> $text',
		'submit' => '<button type="submit" $attrs>$text</button>',
		'error' => '<small $attrs>$text</small>',
	];


	public function form(?array $attrs = [], ?array $content = []): string
	{
		$insertValues = [
			'$attrs' => $this->stringifyMultipleAttrs($attrs),
			'$content' => $this->stringifyArray($content),
		];

		return $this->getTemplateWithInsertedValues($this->templates['form'], $insertValues);
	}

	protected function stringifyArray(?array $content = []): string
	{
		return array_reduce($content, fn($a, $b) => $a.$b) ?? '';
	}

	protected function stringifyAttr(string $attr, string $value = ''): string
	{
		return "$attr=\"$value\"";
	}

	protected function stringifyMultipleAttrs(?array $attrs = []): string
	{
		$result = '';
		foreach ($attrs as $attr => $value) {
			$result .= " $attr=\"$value\"";
		}

		return ltrim($result);
	}

	protected function getTemplateWithInsertedValues(string $template, array $values): string
	{
		$toReplace = array_keys($values);
		$replacings = array_values($values);

		return str_replace($toReplace, $replacings, $template);
	}

	public function formStart(string $attrs): string
	{
		$insertValues = [
			'$attrs' => $this->stringifyMultipleAttrs($attrs),
		];

		return $this->getTemplateWithInsertedValues($this->templates['form_start'], $insertValues);
	}

	public function formEnd(): string
	{
		return $this->templates['form_end'];
	}

	public function label(string $text, string $input = '', ?array $attrs = []): string
	{
		$insertValues = [
			'$text' => $text,
			'$input' => $input,
			'$attrs' => $this->stringifyMultipleAttrs($attrs),
		];

		return $this->getTemplateWithInsertedValues($this->templates['label'], $insertValues);
	}

	public function input(?array $attrs = []): string
	{
		$insertValues = [
			'$attrs' => $this->stringifyMultipleAttrs($attrs),
		];

		return $this->getTemplateWithInsertedValues($this->templates['input'], $insertValues);
	}

	public function hidden(?array $attrs = []): string
	{
		$insertValues = [
			'$attrs' => $this->stringifyMultipleAttrs($attrs),
		];

		return $this->getTemplateWithInsertedValues($this->templates['hidden'], $insertValues);
	}

	public function checkbox(?array $attrs = []): string
	{
		$insertValues = [
			'$attrs' => $this->stringifyMultipleAttrs($attrs),
		];

		return $this->getTemplateWithInsertedValues(
			$this->templates['checkbox'], 
			$insertValues
		);
	}

	public function select(?array $options = [], ?array $attrs = []): string
	{
		$insertValues = [
			'$attrs' => $this->stringifyMultipleAttrs($attrs),
			'$options' => $this->multipleSelectOptions($options),
		];

		return $this->getTemplateWithInsertedValues($this->templates['select'], $insertValues);
	}

	public function multipleSelectOptions(array $options): string
	{
		$result = '';
		foreach ($options as $optionValue => $attrs) {
			$result .= $this->option($optionValue, $attrs);
		}

		return $result;
	}

	public function option(?array $attrs): string
	{
		$insertValues = [
			'$attrs' => $this->stringifyMultipleAttrs($attrs),
		];

		return $this->getTemplateWithInsertedValues($this->templates['option'], $insertValues);
	}

	public function submit(string $text, ?array $attrs = []): string
	{
		$insertValues = [
			'$text' => $text,
			'$attrs' => $this->stringifyMultipleAttrs($attrs),
		];

		return $this->getTemplateWithInsertedValues($this->templates['submit'], $insertValues);
	}

	public function error(string $text, ?array $attrs = []): string
	{
		$insertValues = [
			'$text' => $text,
			'$attrs' => $this->stringifyMultipleAttrs($attrs),
		];

		return $this->getTemplateWithInsertedValues($this->templates['error'], $insertValues);
	}
}
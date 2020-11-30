<?php

namespace App\Forms;

use App\Forms\Views\FormView;
use App\Forms\Validators\FormValidator;
use App\Entities\Token;

abstract class Form
{
	protected FormView $formView;
	protected FormValidator $validator;
	protected Token $token;
	protected array $fieldNames = [];


	public function __construct(
		FormView $formView, 
		FormValidator $validator, 
		?array $fieldNames = []
	)
	{
		$this->formView = $formView;
		$this->validator = $validator;
		$this->fieldNames = $fieldNames;

		$this->insertFormDataIntoViewAndValidator();
	}

	protected function insertFormDataIntoViewAndValidator(): void
	{
		$formData = $this->extractFormDataFromRequest();
		$this->formView->formData = $formData;
		$this->validator->formData = $formData;
	}

	protected function extractFormDataFromRequest(): array
	{
		$data = [];
		foreach ($this->fieldNames as $field) {
			$data[$field] = $_REQUEST[$field] ?? null;
		}

		return $data;
	}

	public function validate(): void
	{
		$this->validator->validate();
		$this->formView->errors = $this->validator->getErrors();
	}

	public function isValid(): bool
	{
		return $this->validator->isValid();
	}

	public function getView(): FormView
	{
		return $this->formView;
	}

	public function getValueOf($field)
	{
		return $this->validator->formData[$field];
	}

	public function getErrors(): array
	{
		return $this->validator->getErrors();
	}

	public function setToken(Token $token): void
	{
		$this->token = $token;
		$this->formView->token = $token;
	}
}
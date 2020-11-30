<?php

namespace App\Forms\Views;

use App\Forms\Views\FormView;

class LoginFormView extends FormView
{
	public function render(): string
	{
		return $this->form([
			'method' => 'POST',
			'class' => 'form card card--sm',
		], [
			$this->formHeading(),
			$this->csrfToken(),
			$this->emailLabelWithInputAndErrors(),
			$this->passwordLabelWithInputAndErrors(),
			$this->preparedSubmitButton(),
		]);
	}

	private function formHeading(): string
	{
		return '<h2 class="form__heading">Sign in</h2>';
	}

	private function csrfToken(): string
	{
		return $this->preparedHiddenInput($this->token->name, $this->token->value);
	}

	private function preparedHiddenInput(string $name, string $value): string
	{
		return $this->hidden([
			'name' => $name,
			'value' => $value,
		]);
	}

	private function emailLabelWithInputAndErrors(): string
	{
		$input = $this->preparedInput('email', 'email', 
			$this->formData['email'] ?? '');
		$errors = $this->preparedFormFieldErrors('email');

		return $this->preparedLabelWithContent('Email', $input.$errors);
	}

	private function passwordLabelWithInputAndErrors(): string
	{
		$input = $this->preparedInput('password', 'password', '');
		$errors = $this->preparedFormFieldErrors('password');

		return $this->preparedLabelWithContent('Password', $input.$errors);
	}

	private function preparedLabelWithContent(string $text, string $content): string
	{
		return $this->label($text, $content, ['class' => 'form__label']);
	}

	private function preparedInput(string $type, string $name, $value): string
	{
		return $this->input([
			'type' => $type,
			'name' => $name,
			'class' => 'form__input',
			'value' => $value,
		]);
	}

	private function preparedFormFieldErrors(string $field): string
	{
		if (empty($this->errors[$field])) {
			return '';
		}

		$errors = array_filter($this->errors[$field], fn($error) => !empty($error));
		$errors = array_map(fn($msg) => $this->preparedError($msg), $errors);

		return $this->stringifyArray($errors);
	}

	private function preparedError(string $text, ?array $attrs = []): string
	{
		return $this->error($text, ['class' => 'form__error']);
	}

	private function preparedSubmitButton(): string
	{
		return $this->submit('Submit', ['class' => 'form__submit button button--primary']);
	}
}
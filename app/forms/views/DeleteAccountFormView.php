<?php

namespace App\Forms\Views;

use App\Forms\Views\FormView;

class DeleteAccountFormView extends FormView
{
	public function render(): string
	{
		return $this->form([
			'method' => 'POST',
			'class' => 'form card card--sm',
		], [
			$this->formHeading(),
			$this->confirmationText(),
			$this->csrfToken(),
			$this->preparedSubmitButton(),
		]);
	}

	private function formHeading(): string
	{
		return '<h2 class="form__heading">Delete account</h2>';
	}

	private function csrfToken(): string
	{
		return $this->preparedHiddenInput($this->token->name, $this->token->value);
	}

	private function confirmationText(): string
	{
		return '<p>Are You sure You want to delete your account?</p>'.
				'<p>This action is irreversable.</p>';
	}

	private function preparedHiddenInput(string $name, string $value): string
	{
		return $this->hidden([
			'name' => $name,
			'value' => $value,
		]);
	}

	private function preparedSubmitButton(): string
	{
		return $this->submit('Submit', ['class' => 'form__submit button button--danger']);
	}
}
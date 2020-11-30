<?php

namespace App\Forms;

use App\Forms\Form;
use App\Forms\Views\FormView;
use App\Forms\Validators\FormValidator;

class DeleteAccountForm extends Form
{
	public function __construct(FormView $formView, FormValidator $validator)
	{
		parent::__construct($formView, $validator);
	}
}
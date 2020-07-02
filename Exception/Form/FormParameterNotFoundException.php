<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Exception\Form;

class FormParameterNotFoundException extends FormException {
	public function __construct() {
		parent::__construct("Please fill all fields.");
	}
}
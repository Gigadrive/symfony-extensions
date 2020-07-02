<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Exception\Form;

class FormInvalidImageException extends FormException {
	public function __construct() {
		parent::__construct("Please upload a valid image.");
	}
}
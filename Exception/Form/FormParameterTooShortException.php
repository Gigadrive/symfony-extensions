<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Exception\Form;

class FormParameterTooShortException extends FormException {
	public function __construct(string $paramName, int $minLength) {
		parent::__construct("The " . $paramName . " has to be at least " . $minLength . " character" . ($minLength !== 1 ? "s" : "") . " long.");
	}
}
<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Exception\Form;

class FormParameterTooLongException extends FormException {
	public function __construct(string $paramName, int $maxLength) {
		parent::__construct("The " . $paramName . " has can not be longer than " . $maxLength . " character" . ($maxLength !== 1 ? "s" : "") . ".");
	}
}
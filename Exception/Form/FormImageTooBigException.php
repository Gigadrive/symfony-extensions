<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Exception\Form;

class FormImageTooBigException extends FormException {
	public function __construct(int $maxFileSize) {
		parent::__construct("The image may not be bigger than " . $maxFileSize . "MB.");
	}
}
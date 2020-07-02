<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Exception\Form;

use Exception;

class FormException extends Exception {
	public function __construct($message = "An error occurred.") {
		parent::__construct($message);
	}
}
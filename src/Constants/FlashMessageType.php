<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Constants;

class FlashMessageType {
	/**
	 * @var string SUCCESS A green flash message indicating an action has been executed successfully.
	 */
	public const SUCCESS = "success";

	/**
	 * @var string ERROR A red flash message indicating an action has failed to be executed successfully.
	 */
	public const ERROR = "error";

	/**
	 * @var string INFO A blue flash message indicating information about a current status.
	 */
	public const INFO = "info";
}
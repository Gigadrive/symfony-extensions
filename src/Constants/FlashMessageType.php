<?php
/**
 * Copyright (C) 2018-2020 Gigadrive - All rights reserved.
 * https://gigadrivegroup.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://gnu.org/licenses/>
 */

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
<?php
/*
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

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Service;

use Exception;
use Gumlet\ImageResize;
use Gumlet\ImageResizeException;
use Psr\Log\LoggerInterface;
use function file_exists;
use function filesize;
use function get_class;
use function getimagesize;

class ImageProcessingService {
	/**
	 * @var LoggerInterface $logger
	 */
	protected $logger;

	public function __construct(LoggerInterface $logger) {
		$this->logger = $logger;
	}

	/**
	 * @param string $path
	 * @param int $width
	 * @param int $height
	 * @param bool $allowEnlarge
	 * @return bool
	 * @author Mehdi Baaboura <mbaaboura@gigadrivegroup.com>
	 */
	public function cropImage(string $path, int $width, int $height, bool $allowEnlarge = true): bool {
		if (!$this->isValidImage($path)) {
			return false;
		}

		try {
			$imageResize = new ImageResize($path);
			$imageResize->crop($width, $height, $allowEnlarge);

			$imageResize->save($path);

			return true;
		} catch (Exception $e) {
			$this->logger->error("Failed to crop image from path " . $path . " (" . $e->getMessage() . ")", [
				"exceptionType" => get_class($e),
				"exceptionMessage" => $e->getMessage(),
				"exception" => $e
			]);

			return false;
		}
	}

	/**
	 * @param string $path
	 * @return bool
	 * @author Mehdi Baaboura <mbaaboura@gigadrivegroup.com>
	 */
	public function isValidImage(string $path): bool {
		if (!file_exists($path)) {
			return false;
		}

		if (!(@getimagesize($path))) {
			return false;
		}

		if (!(@filesize($path))) {
			return false;
		}

		try {
			new ImageResize($path);
			return true;
		} catch (ImageResizeException $e) {
			return false;
		}
	}
}
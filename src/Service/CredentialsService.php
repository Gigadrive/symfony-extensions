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

use Symfony\Component\HttpKernel\KernelInterface;
use function array_key_exists;
use function file_get_contents;
use function is_null;
use function json_decode;

class CredentialsService {
	/**
	 * @var KernelInterface $kernel
	 */
	protected $kernel;

	/**
	 * @var string $filePath
	 */
	protected $filePath;

	public function __construct(KernelInterface $kernel) {
		$this->kernel = $kernel;

		$this->filePath = $this->kernel->getProjectDir() . "/credentials_store.json";
	}

	/**
	 * @param string|null $space
	 * @return array
	 * @author Mehdi Baaboura <mbaaboura@gigadrivegroup.com>
	 */
	public function getKeys(?string $space = null): array {
		if (is_null($space)) {
			return $this->parsedFileContent();
		}

		$keys = $this->getKeys();
		if (!array_key_exists($space, $keys)) {
			return [];
		}

		return $keys[$space];
	}

	protected function parsedFileContent() {
		return json_decode(file_get_contents($this->filePath), true);
	}
}
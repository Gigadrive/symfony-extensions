<?php
/*
 * Copyright (C) 2018-2021 Gigadrive - All rights reserved.
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

use Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection\Util;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AssetService {
	/**
	 * @var UrlGeneratorInterface $urlGenerator
	 */
	private $urlGenerator;

	/**
	 * @var KernelInterface $kernel
	 */
	private $kernel;

	/**
	 * @var RouterInterface $router
	 */
	private $router;

	public function __construct(UrlGeneratorInterface $urlGenerator, KernelInterface $kernel, RouterInterface $router) {
		$this->urlGenerator = $urlGenerator;
		$this->kernel = $kernel;
		$this->router = $router;
	}

	/**
	 * @param bool $absolute
	 * @return string|null
	 * @author Mehdi Baaboura <mbaaboura@gigadrivegroup.com>
	 */
	public function getJSWebpackFile(bool $absolute = false): ?string {
		return $this->getBundleFile($this->getPathToPublicFolder() . "build/bundle.js", $absolute);
	}

	/**
	 * @param string $pattern
	 * @param bool $absolute
	 * @param bool $forceSSL
	 * @return string|null
	 * @author Mehdi Baaboura <mbaaboura@gigadrivegroup.com>
	 */
	public function getBundleFile(string $pattern, bool $absolute = false, bool $forceSSL = true): ?string {
		$results = glob($pattern);
		if ($results && count($results) > 0) {
			if (count($results) > 1) {
				usort($results, function ($a, $b) {
					$aTime = filemtime($a);
					$bTime = filemtime($b);

					return ($aTime === $bTime) ? 0 : (($aTime > $bTime) ? -1 : 1);
				});
			}

			$result = $results[0];

			$fileName = basename($result);
			if (!$absolute) return $fileName;

			$fileName = $fileName . "?v=" . filemtime($result);

			$base = $this->router->getContext()->getBaseUrl();

			$final = $base . "build/" . $fileName;
			return $forceSSL ? Util::forceSSL($final) : $final;
		}

		return null;
	}

	/**
	 * @return string
	 * @author Mehdi Baaboura <mbaaboura@gigadrivegroup.com>
	 */
	public function getPathToPublicFolder(): string {
		return $this->kernel->getProjectDir() . "/public/";
	}

	/**
	 * @param bool $absolute
	 * @return string|null
	 * @author Mehdi Baaboura <mbaaboura@gigadrivegroup.com>
	 */
	public function getCSSWebpackFile(bool $absolute = false): ?string {
		return $this->getBundleFile($this->getPathToPublicFolder() . "build/bundle.css", $absolute);
	}
}
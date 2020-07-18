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

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Twig;

use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection\Util;
use Gigadrive\Bundle\SymfonyExtensionsBundle\Service\GigadriveGeneralService;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GigadriveTwigExtension extends AbstractExtension {
	/**
	 * @var EntityManagerInterface $entityManager
	 */
	private $entityManager;

	/**
	 * @var GigadriveGeneralService $generalService
	 */
	private $generalService;

	public function __construct(
		EntityManagerInterface $entityManager,
		GigadriveGeneralService $generalService
	) {
		$this->entityManager = $entityManager;
		$this->generalService = $generalService;
	}

	public function getFunctions() {
		return [
			new TwigFunction("currentPath", function (array $additionalParameters = []): ?string {
				return $this->generalService->currentPath($additionalParameters);
			}),

			new TwigFunction("currentURL", function (array $additionalParameters = []): ?string {
				return $this->generalService->currentURL($additionalParameters);
			}),

			new TwigFunction("env", function (string $name): ?string {
				if (!array_key_exists($name, $_ENV)) return null;

				$var = $_ENV[$name];

				return !Util::isEmpty($var) ? $var : null;
			}),

			new TwigFunction("linkify", function ($value, $protocols = ["http", "mail"], array $attributes = []): Markup {
				return new Markup(Util::linkify($value, $protocols, $attributes), "UTF-8");
			})
		];
	}

	public function getFilters() {
		return [
			new TwigFilter("lineBreaks", function ($string) {
				return new Markup(str_replace(PHP_EOL, "<br/>", $string), "UTF-8");
			}),

			new TwigFilter("shuffle", function ($array) {
				shuffle($array);

				return $array;
			}),

			new TwigFilter("capitalizeHuman", function ($string) {
				return Util::capitalizeHuman($string);
			}),

			new TwigFilter("url_decode", function ($string) {
				return urldecode($string);
			}),

			new TwigFilter("countReadable", function ($number) {
				return $number === -1 ? "Unlimited" : number_format($number);
			}),

			new TwigFilter("timeago", function ($timestamp) {
				$str = 0;

				if ($timestamp instanceof DateTimeInterface) {
					$str = $timestamp->getTimestamp();
				} else {
					$str = strtotime($timestamp);
				}

				$timestamp = date("Y", $str) . "-" . date("m", $str) . "-" . date("d", $str) . "T" . date("H", $str) . ":" . date("i", $str) . ":" . date("s", $str) . "Z";

				return new Markup('<time class="timeago" datetime="' . $timestamp . '" title="' . date("d", $str) . "." . date("m", $str) . "." . date("Y", $str) . " " . date("H", $str) . ":" . date("i", $str) . ":" . date("s", $str) . ' UTC">' . $timestamp . '</time>', "UTF-8");
			})
		];
	}
}
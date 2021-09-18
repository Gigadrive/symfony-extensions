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

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Twig;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection\Util;
use Gigadrive\Bundle\SymfonyExtensionsBundle\Service\GigadriveGeneralService;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;
use Twig\TwigFunction;
use function array_slice;
use function floor;
use function implode;
use function is_empty;
use function str_contains;
use function strtolower;

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
		EntityManagerInterface  $entityManager,
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

				return !is_empty($var) ? $var : null;
			}),

			new TwigFunction("deviceDataToIconClass", function (array $terms) {
				$icons = [
					"Xbox" => "xbox",
					"PlayStation" => "playstation",
					"Macintosh" => "apple",
					"iPhone" => "apple",
					"iPad" => "apple",
					"iPod" => "apple",
					"Android" => "android",
					"BlackBerry" => "blackberry",
					"Kindle" => "amazon",
					"Firefox" => "firefox-browser",
					"Safari" => "safari",
					"Internet Explorer" => "internet-explorer",
					"Chrome" => "chrome",
					"Opera" => "opera",
					"Edge" => "edge",
					"Windows" => "windows",
					"Linux" => "linux",
					"Ubuntu" => "ubuntu"
				];

				foreach ($terms as $term) {
					foreach ($icons as $iconName => $iconClass) {
						if (str_contains(strtolower($term), strtolower($iconName))) {
							return "fab fa-" . $iconClass;
						}
					}
				}

				return "fas fa-globe";
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
			}),

			// https://stackoverflow.com/a/18602474/4117923
			new TwigFilter("agoShort", function ($dateTime) {
				$now = new DateTime;
				$diff = $now->diff($dateTime);

				$diff->w = floor($diff->d / 7);
				$diff->d -= $diff->w * 7;

				$string = [
					'y' => 'y',
					'm' => 'mo',
					'w' => 'w',
					'd' => 'd',
					'h' => 'h',
					'i' => 'm',
					's' => 's',
				];

				foreach ($string as $k => &$v) {
					if ($diff->$k) {
						$v = $diff->$k . $v;
					} else {
						unset($string[$k]);
					}
				}

				$string = array_slice($string, 0, 1);

				return $string ? implode(', ', $string) : "now";
			})
		];
	}
}
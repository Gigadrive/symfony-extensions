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

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection;

class Util {
	/**
	 * Returns a random string of characters
	 *
	 * @access public
	 * @param int $length The maximum length of the string (the actual length will be something between this number and the half of it)
	 * @return string
	 */
	public static function getRandomString($length = 16): string {
		$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$charactersLength = strlen($characters);
		$randomString = "";
		for ($i = 0; $i < rand($length / 2, $length); $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	/**
	 * Returns a string that fixes exploits like a zero-width space
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function fixString($string): string {
		return preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', str_replace("\xE2\x80\x8B", "", str_replace("\xE2\x80\xAE", "", $string)));
	}

	/**
	 * Returns wheter a string or array is empty
	 *
	 * @access public
	 * @param string|array $var
	 * @return bool
	 */
	public static function isEmpty($var): bool {
		if (is_array($var)) {
			return count($var) == 0;
		} else if (is_string($var)) {
			$var = self::fixString($var);

			return $var == "" || trim($var) == "" || str_replace(" ", "", str_replace(" ", "", $var)) == "" || strlen($var) == 0;
		} else {
			return is_null($var) || empty($var);
		}
	}

	/**
	 * Checks whether a string contains another string
	 *
	 * @access public
	 * @param string $string The full string
	 * @param string $check The substring to be checked
	 * @return bool
	 */
	public static function contains($string, $check): bool {
		return strpos($string, $check) !== false;
	}

	/**
	 * Returns a sanatized string that avoids prepending or traling spaces and XSS attacks
	 *
	 * @access public
	 * @param string $string The string to sanatize
	 * @return string
	 */
	public static function sanatizeString($string): string {
		return trim(htmlentities($string));
	}

	/**
	 * Opposite of sanatizeString()
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function desanatizeString($string): string {
		return html_entity_decode($string);
	}

	/**
	 * Returns a sanatzied string to use in HTML attributes (avoids problems with quotations)
	 *
	 * @access public
	 * @param string $string The string to sanatize
	 * @return string
	 */
	public static function sanatizeHTMLAttribute($string): string {
		return trim(htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, "UTF-8"));
	}

	/**
	 * Limits a string to a specific length and adds "..." to the end if needed
	 *
	 * @access public
	 * @param string $string
	 * @param int $length
	 * @param bool $addDots
	 * @return string
	 */
	public static function limitString($string, $length, $addDots = false) {
		if (strlen($string) > $length)
			$string = substr($string, 0, ($addDots ? $length - 3 : $length)) . ($addDots ? "..." : "");
		return $string;
	}

	/**
	 * Finds all URLs in a string
	 *
	 * @param string $text The string to check
	 * @return string[] The URLs that were found
	 */
	public static function getURLsInString(string $text): array {
		// https://stackoverflow.com/a/23367301/4117923

		$pattern = "~[a-z]+://\S+~";
		if (preg_match_all($pattern, $text, $out)) {
			return $out[0];
		}

		return [];
	}

	/**
	 * Gets whether a string starts with another
	 *
	 * @access public
	 * @param string $string The string in subject
	 * @param string $start The string to be checked whether it is the start of $string
	 * @param bool $ignoreCase If true, the case of the strings won't affect the result
	 * @return bool
	 */
	public static function startsWith(string $string, string $start, bool $ignoreCase = false): bool {
		if (strlen($start) <= strlen($string)) {
			if ($ignoreCase == true) {
				return substr($string, 0, strlen($start)) == $start;
			} else {
				return strtolower(substr($string, 0, strlen($start))) == strtolower($start);
			}
		} else {
			return false;
		}
	}

	/**
	 * Gets whether a string ends with another
	 *
	 * @access public
	 * @param string $string The string in subject
	 * @param string $end The string to be checked whether it is the end of $string
	 * @return bool
	 */
	public static function endsWith(string $string, string $end): bool {
		$length = strlen($end);
		return $length === 0 ? true : (substr($string, -$length) === $end);
	}

	/**
	 * @param string $text
	 * @return array
	 */
	public static function extractHashtags(string $text): array {
		$results = [];

		foreach (explode(" ", str_replace(PHP_EOL, " ", $text)) as $part) {
			if (!self::startsWith($part, "#")) continue;

			// https://stackoverflow.com/a/16609221/4117923
			preg_match_all("/(#\w+)/u", $part, $matches);
			if ($matches && is_array($matches)) {
				$hashtagsArray = array_count_values($matches[0]);
				$hashtags = array_keys($hashtagsArray);

				foreach ($hashtags as $hashtag) {
					if (!self::startsWith($hashtag, "#")) continue;
					$hashtag = substr($hashtag, 1);

					if (strlen($hashtag) > 64) continue;

					if (count($results) > 0) {
						// filter duplicates
						foreach ($results as $result) {
							if (strtoupper($result) === $hashtag) continue;
						}
					}

					$results[] = $hashtag;
				}
			}
		}

		return $results;
	}

	/**
	 * @param int $number
	 * @return bool
	 */
	public static function isEven(int $number): bool {
		return $number % 2 === 0;
	}

	/**
	 * @param int $number
	 * @return bool
	 */
	public static function isOdd(int $number): bool {
		return !self::isEven($number);
	}

	/**
	 * @param string $string
	 * @param bool $doMultiple
	 * @return string
	 */
	public static function capitalizeHuman(string $string, bool $doMultiple = true): string {
		$length = strlen($string);

		if ($length === 0) return $string;
		if ($length === 1) return strtoupper($string);

		if (!$doMultiple) {
			return strtoupper(substr($string, 0, 1)) . strtolower(substr($string, 1, $length - 1));
		}

		$s = "";

		foreach (explode("_", $string) as $part) {
			$s .= self::capitalizeHuman($part, false) . " ";
		}

		return trim($s);
	}

	/**
	 * Turn all URLs in clickable links.
	 *
	 * @see https://gist.github.com/jasny/2000705
	 * @param string $value
	 * @param array $protocols http/https, ftp, mail, twitter
	 * @param array $attributes
	 * @return string
	 */
	public static function linkify($value, $protocols = ["http", "mail"], array $attributes = []) {
		// Link attributes
		$attr = '';
		foreach ($attributes as $key => $val) {
			$attr .= ' ' . $key . '="' . htmlentities($val) . '"';
		}

		$links = [];

		// Extract existing links and tags
		$value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) {
			return '<' . array_push($links, $match[1]) . '>';
		}, $value);

		// Extract text links for each protocol
		foreach ((array)$protocols as $protocol) {
			switch ($protocol) {
				case 'http':
				case 'https':
					$value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) {
						if ($match[1]) $protocol = $match[1];
						$link = $match[2] ?: $match[3];
						return '<' . array_push($links, "<a $attr href=\"$protocol://$link\">$link</a>") . '>';
					}, $value);
					break;
				case 'mail':
					$value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) {
						return '<' . array_push($links, "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>';
					}, $value);
					break;
				case 'twitter':
					$value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) {
						return '<' . array_push($links, "<a $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1] . "\">{$match[0]}</a>") . '>';
					}, $value);
					break;
				default:
					$value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) {
						return '<' . array_push($links, "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>';
					}, $value);
					break;
			}
		}

		// Insert all link
		return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) {
			return $links[$match[1] - 1];
		}, $value);
	}

	/**
	 * @param string $url
	 * @return string
	 */
	public static function forceSSL(string $url): string {
		if (self::contains($url, "http://localhost") || self::contains($url, "http://127.0.0.1")) return $url;

		return str_replace("http://", "https://", $url);
	}

	/**
	 * @param array|string $var
	 * @param $default
	 * @return array|string|null
	 */
	public static function def($var, $default = null) {
		return Util::isEmpty($var) ? $default : $var;
	}

	/**
	 * @return bool
	 */
	public static function isGitInstalled(): bool {
		// FIXME: Does not work on Windows
		return !!`which git`;
	}

	/**
	 * @see https://stackoverflow.com/a/3338133/4117923
	 * @param $dir
	 */
	public static function recursiveRemoveDirectory($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
						self::recursiveRemoveDirectory($dir . DIRECTORY_SEPARATOR . $object);
					else
						unlink($dir . DIRECTORY_SEPARATOR . $object);
				}
			}
			rmdir($dir);
		}
	}

	/**
	 * @see https://stackoverflow.com/a/145348/4117923
	 * @param array $array
	 * @return array
	 */
	public static function filterMultiDimensionFromArray(array $array): array {
		$final = [];

		foreach ($array as $key => $value) {
			if (is_array($value)) continue;

			$final[$key] = $value;
		}

		return $final;
	}
}
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

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\SEO\Entry;

use Gigadrive\Bundle\SymfonyExtensionsBundle\Constants\SEOConstants;
use Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection\Util;
use Gigadrive\Bundle\SymfonyExtensionsBundle\SEO\MetaTagEntry;
use function sprintf;

class DescriptionMetaTagEntry extends MetaTagEntry {
	/**
	 * @var string $url
	 */
	private $text;

	public function __construct(?string $text = null) {
		if (Util::isEmpty($text)) {
			$this->text = ($_ENV["DEFAULT_DESCRIPTION"] ?: "");
			return;
		}

		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getText(): string {
		return $this->text;
	}

	/**
	 * @param string $text
	 * @return DescriptionMetaTagEntry
	 */
	public function setText(string $text): self {
		$this->text = Util::limitString($text, SEOConstants::META_DESCRIPTION_LENGTH, true);

		return $this;
	}

	public function getAsOGTagString(): string {
		return sprintf(
			'<meta name="og:description" content="%s"/>',
			$this->text
		);
	}

	public function getAsTwitterTagString(): string {
		return sprintf(
			'<meta name="twitter:description" content="%s"/>',
			$this->text
		);
	}

	public function getAsRegularTagString(): string {
		return sprintf(
			'<meta name="description" content="%s"/>',
			$this->text
		);
	}
}
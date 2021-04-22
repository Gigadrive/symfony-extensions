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

use Gigadrive\Bundle\SymfonyExtensionsBundle\SEO\MetaTagEntry;
use function sprintf;

class BigImageMetaTagEntry extends MetaTagEntry {
	/**
	 * @var string $url
	 */
	private $url;

	public function __construct(string $url) {
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getURL(): string {
		return $this->url;
	}

	/**
	 * @param string $url
	 * @return BigImageMetaTagEntry
	 */
	public function setURL(string $url): self {
		$this->url = $url;

		return $this;
	}

	public function getAsTwitterTagString(): string {
		return sprintf(
			'<meta property="twitter:image" content="%s"/><meta name="twitter:card" content="summary_large_image" />',
			$this->url
		);
	}
}
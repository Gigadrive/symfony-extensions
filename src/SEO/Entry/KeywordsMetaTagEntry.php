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
use function implode;
use function sprintf;

class KeywordsMetaTagEntry extends MetaTagEntry {
	/**
	 * @var array $keywords
	 */
	private $keywords;

	public function __construct(array $keywords = []) {
		$this->keywords = $keywords;
	}

	/**
	 * @return array
	 */
	public function getKeywords(): array {
		return $this->keywords;
	}

	/**
	 * @param array $keywords
	 */
	public function setKeywords(array $keywords): void {
		$this->keywords = $keywords;
	}

	public function getAsRegularTagString(): string {
		return sprintf(
			'<meta name="keywords" content="%s"/>',
			implode(",", $this->keywords)
		);
	}
}
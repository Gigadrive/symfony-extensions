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

class FaviconMetaTagEntry extends MetaTagEntry {
	/**
	 * @var string $url
	 */
	protected $url;
	/**
	 * @var int $size
	 */
	protected $size;
	/**
	 * @var string $type
	 */
	protected $type;

	public function __construct(string $url, int $size = 16, string $type = "image/png") {
		$this->url = $url;
		$this->size = $size;
		$this->type = $type;
	}

	/**
	 * @return int
	 */
	public function getSize(): int {
		return $this->size;
	}

	/**
	 * @param int $size
	 */
	public function setSize(int $size): void {
		$this->size = $size;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl(string $url): void {
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type): void {
		$this->type = $type;
	}

	public function getAsRegularTagString(): string {
		return sprintf(
			'<link rel="icon" type="%s" sizes="%dx%d" href="%s"/>',
			$this->type,
			$this->size,
			$this->size,
			$this->url
		);
	}
}
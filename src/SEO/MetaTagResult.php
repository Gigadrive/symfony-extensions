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

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\SEO;

use Gigadrive\Bundle\SymfonyExtensionsBundle\SEO\Entry\DescriptionMetaTagEntry;

class MetaTagResult {
	/**
	 * @var MetaTagEntry[] $entries
	 */
	private $entries;

	public function __construct() {
		$this->entries = [new DescriptionMetaTagEntry()];
	}

	/**
	 * @param MetaTagEntry $entry
	 * @return $this
	 */
	public function addEntry(MetaTagEntry $entry): self {
		$entries = [];

		foreach ($this->entries as $loopEntry) {
			if (get_class($loopEntry) !== get_class($entry)) {
				$entries[] = $loopEntry;
			}
		}

		$entries[] = $entry;
		$this->entries = $entries;

		return $this;
	}

	public function getAsHTMLTags(): string {
		$s = "";

		foreach ($this->entries as $entry) {
			$s .= $entry->getAsOGTagString();
			$s .= $entry->getAsTwitterTagString();
			$s .= $entry->getAsRegularTagString();
		}

		return $s;
	}
}
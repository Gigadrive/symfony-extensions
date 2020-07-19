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

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Service\Database\Pagination;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use function strval;

class Pagination {
	/**
	 * @var Paginator $paginator
	 */
	private $paginator;

	/**
	 * @var int $currentPage
	 */
	private $currentPage;

	/**
	 * @var QueryBuilder|null $totalQuery
	 */
	private $totalQuery;

	public function __construct(Paginator $paginator, int $currentPage = 1, ?QueryBuilder $totalQuery = null) {
		$this->paginator = $paginator;
		$this->currentPage = $currentPage;
		$this->totalQuery = $totalQuery;
	}

	/**
	 * @return Paginator
	 */
	public function getDoctrinePaginator(): Paginator {
		return $this->paginator;
	}

	/**
	 * @return QueryBuilder|null
	 */
	public function getTotalQuery(): ?QueryBuilder {
		return $this->totalQuery;
	}

	/**
	 * @return string[]
	 * @see https://github.com/jasongrimes/php-paginator/blob/master/src/JasonGrimes/Paginator.php#L197
	 */
	public function getPages(): array {
		$pages = [];

		$numPages = $this->getLastPage();
		$maxPagesToShow = 10;

		if ($numPages <= 1) {
			return [];
		}

		if ($numPages <= $maxPagesToShow) {
			for ($i = 1; $i <= $numPages; $i++) {
				$pages[] = strval($i);
			}
		} else {
			// Determine the sliding range, centered around the current page.
			$numAdjacents = (int)floor(($maxPagesToShow - 3) / 2);

			if ($this->currentPage + $numAdjacents > $numPages) {
				$slidingStart = $numPages - $maxPagesToShow + 2;
			} else {
				$slidingStart = $this->currentPage - $numAdjacents;
			}

			if ($slidingStart < 2) $slidingStart = 2;
			$slidingEnd = $slidingStart + $maxPagesToShow - 3;
			if ($slidingEnd >= $numPages) $slidingEnd = $numPages - 1;

			// Build the list of pages.
			$pages[] = "1";

			if ($slidingStart > 2) {
				$pages[] = "E";
			}

			for ($i = $slidingStart; $i <= $slidingEnd; $i++) {
				$pages[] = strval($i);
			}

			if ($slidingEnd < $numPages - 1) {
				$pages[] = "E";
			}

			$pages[] = $numPages;
		}


		return $pages;
	}

	/**
	 * @return int
	 */
	public function getLastPage(): int {
		return ceil($this->getTotal() / $this->paginator->getQuery()->getMaxResults());
	}

	/**
	 * @return int
	 */
	public function getTotal(): int {
		if ($this->totalQuery) {
			return $this->totalQuery
				->getQuery()
				->useQueryCache(true)
				->getSingleScalarResult();
		}

		return $this->paginator->count();
	}

	/**
	 * @return bool
	 */
	public function currentPageHasNoResult(): bool {
		return !$this->paginator->getIterator()->count();
	}

	/**
	 * @return array
	 */
	public function getResult(): array {
		return $this->paginator->getQuery()->getResult();
	}

	/**
	 * @return int
	 */
	public function getCurrentPage(): int {
		return $this->currentPage;
	}
}
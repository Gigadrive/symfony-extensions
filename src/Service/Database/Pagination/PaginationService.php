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

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Gigadrive\Bundle\SymfonyExtensionsBundle\Service\GigadriveGeneralService;

class PaginationService {
	/**
	 * @var GigadriveGeneralService $generalService
	 */
	private $generalService;

	public function __construct(GigadriveGeneralService $generalService) {
		$this->generalService = $generalService;
	}

	/**
	 * @param QueryBuilder|Query $query
	 * @param int $itemsPerPage
	 * @return Pagination
	 */
	public function paginate($query, int $itemsPerPage = 15): Pagination {
		$request = $this->generalService->currentRequest;
		$currentPage = $request && $request->attributes->has("_route_params") && isset($request->attributes->get("_route_params")["page"]) ? ((int)$request->attributes->get("_route_params")["page"]) : ($request->query->getInt("p") ?: 1);

		$paginator = (new Paginator($query));
		$paginator->getQuery()
			->useQueryCache(true)
			->setFirstResult($itemsPerPage * ($currentPage - 1))
			->setMaxResults($itemsPerPage);

		return new Pagination($paginator, $currentPage);
	}
}
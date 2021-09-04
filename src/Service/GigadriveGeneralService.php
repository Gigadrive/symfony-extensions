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

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection\Util;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function array_key_exists;
use function array_merge;

class GigadriveGeneralService {
	/**
	 * @var EntityManagerInterface $entityManager
	 */
	public $entityManager;

	/**
	 * @var RequestStack $requestStack
	 */
	public $requestStack;

	/**
	 * @var Request|null $currentRequest
	 */
	public $currentRequest;

	/**
	 * @var UrlGeneratorInterface $urlGenerator
	 */
	public $urlGenerator;

	/**
	 * @var LoggerInterface $logger
	 */
	public $logger;

	/**
	 * @var CredentialsService $credentials
	 */
	public $credentials;

	public function __construct(
		EntityManagerInterface $entityManager,
		RequestStack $requestStack,
		UrlGeneratorInterface $urlGenerator,
		LoggerInterface $logger,
		CredentialsService $credentials
	) {
		$this->entityManager = $entityManager;
		$this->requestStack = $requestStack;
		$this->urlGenerator = $urlGenerator;
		$this->logger = $logger;
		$this->credentials = $credentials;

		$this->currentRequest = $requestStack->getMasterRequest();
	}

	public function currentPath(array $additionalParameters = [], bool $fixParametersBug = true): ?string {
		$currentRequest = $this->currentRequest;
		if (is_null($currentRequest)) return null;

		$route = $currentRequest->attributes->get('_route');
		$params = $currentRequest->attributes->get('_route_params') ?: [];

		$parameters = array_merge($params, $currentRequest->query->all(), $additionalParameters);
		if ($fixParametersBug && array_key_exists("params", $parameters)) {
			unset($parameters["params"]);
		}

		return $this->urlGenerator->generate($route, $parameters);
	}

	public function currentURL(array $additionalParameters = [], bool $fixParametersBug = true): ?string {
		$currentRequest = $this->currentRequest;
		if (is_null($currentRequest)) return null;

		$route = $currentRequest->attributes->get('_route');
		$params = $currentRequest->attributes->get('_route_params') ?: [];

		$parameters = array_merge($params, $currentRequest->query->all(), $additionalParameters);
		if ($fixParametersBug && array_key_exists("params", $parameters)) {
			unset($parameters["params"]);
		}

		return Util::forceSSL($this->urlGenerator->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL));
	}
}
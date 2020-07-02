<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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

	public function __construct(
		EntityManagerInterface $entityManager,
		RequestStack $requestStack
	) {
		$this->entityManager = $entityManager;
		$this->requestStack = $requestStack;

		$this->currentRequest = $requestStack->getCurrentRequest();
	}
}
<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Controller;

use Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection\GigadriveGeneralService;
use Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection\Util;
use Gigadrive\Bundle\SymfonyExtensionsBundle\Exception\Form\FormParameterNotFoundException;
use Gigadrive\Bundle\SymfonyExtensionsBundle\Exception\Form\FormParameterTooLongException;
use Gigadrive\Bundle\SymfonyExtensionsBundle\Exception\Form\FormParameterTooShortException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class GigadriveController extends AbstractController {
	/**
	 * @var GigadriveGeneralService $generalService
	 */
	protected $generalService;

	public function __construct(GigadriveGeneralService $generalService) {
		$this->generalService = $generalService;
	}

	/**
	 * @param null $token
	 * @return bool
	 */
	public function csrf($token = null): bool {
		if (is_null($token) && !is_null($this->generalService->currentRequest) && $this->generalService->currentRequest->isMethod("POST")) {
			if ($this->generalService->currentRequest->request->has("_csrf_token")) {
				$token = $this->generalService->currentRequest->request->get("_csrf_token");
			}
		}

		return !is_null($token) && $this->isCsrfTokenValid("csrf", $token);
	}

	public function stringParam(string $key, int $min = 0, int $max = PHP_INT_MAX): ?string {
		$value = $this->readParameter($key);
		$length = strlen($value);

		if ($length < $min) {
			throw new FormParameterTooShortException($key, $min);
		}

		if ($length > $max) {
			throw new FormParameterTooLongException($key, $max);
		}

		return trim($value);
	}

	public function readParameter(string $key, ?ParameterBag $parameterBag = null): string {
		if (is_null($parameterBag) && !is_null($this->generalService->currentRequest)) {
			$parameterBag = $this->generalService->currentRequest->request;
		}

		if (is_null($parameterBag) || !$parameterBag->has($key)) {
			throw new FormParameterNotFoundException();
		}

		return Util::fixString($parameterBag->get($key));
	}

	public function readCheckbox(string $key, ?ParameterBag $parameterBag = null, bool $default = false): bool {
		if (is_null($parameterBag) && !is_null($this->generalService->currentRequest)) {
			$parameterBag = $this->generalService->currentRequest->request;
		}

		if (is_null($parameterBag)) {
			return $default;
		}

		if (!$parameterBag->has($key)) {
			return $default;
		}

		return $parameterBag->get($key) === "on";
	}

	public function query(string $key, ?ParameterBag $parameterBag = null, ?string $default = null): ?string {
		if (is_null($parameterBag) && !is_null($this->generalService->currentRequest)) {
			$parameterBag = $this->generalService->currentRequest->query;
		}

		if (is_null($parameterBag)) {
			return $default;
		}

		if (!$parameterBag->has($key)) {
			return $default;
		}

		return $parameterBag->get($key);
	}
}
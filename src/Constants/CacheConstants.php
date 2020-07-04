<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Cache;

class CacheConstants {
	/**
	 * @var int RESULT_CACHE_LIFETIME_SHORT
	 */
	public const RESULT_CACHE_LIFETIME_VERY_SHORT = 10;

	/**
	 * @var int RESULT_CACHE_LIFETIME_SHORT
	 */
	public const RESULT_CACHE_LIFETIME_SHORT = 30;

	/**
	 * @var int RESULT_CACHE_LIFETIME
	 */
	public const RESULT_CACHE_LIFETIME = 3 * 60;

	/**
	 * @var int RESULT_CACHE_LIFETIME
	 */
	public const RESULT_CACHE_LIFETIME_LONG = 10 * 60;
}
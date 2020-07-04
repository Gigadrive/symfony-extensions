<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
	/**
	 * @var bool $debug
	 */
	private $debug = false;

	/**
	 * @var NodeBuilder $builder
	 */
	private $builder;

	public function __construct($debug) {
		$this->debug = (bool)$debug;
		$this->builder = new NodeBuilder();
	}

	public function getConfigTreeBuilder() {
		$treeBuilder = new TreeBuilder("symfony_extensions", "array", $this->builder);
		$root = $treeBuilder->getRootNode();

		$root
			->addDefaultsIfNotSet()
			->children()
			->append($this->createCacheNode());

		return $treeBuilder;
	}

	private function createCacheNode(): NodeDefinition {
		return $this->builder
			->scalarNode("cache_name")
			->info("The name used for default cache handling")
			->defaultValue("gigadrive-symfony");
	}
}
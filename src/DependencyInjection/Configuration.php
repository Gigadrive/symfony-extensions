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
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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class GigadriveSymfonyExtensionsExtension extends Extension {
	public function load(array $configs, ContainerBuilder $container) {
		$this->processConfiguration(
			new Configuration($container->getParameter("kernel.debug")),
			$configs
		);

		$this->addAnnotatedClassesToCompile([]);

		$configFiles = ["services.yaml"];

		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__ . "/../Resources/config")
		);

		foreach ($configFiles as $configFile) {
			$loader->load($configFile);
		}
	}
}
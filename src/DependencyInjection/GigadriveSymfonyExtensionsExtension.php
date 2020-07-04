<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class GigadriveSymfonyExtensionsExtension extends Extension {
	public function load(array $configs, ContainerBuilder $container) {
		$this->processConfiguration(
			new Configuration($container->getParameter("kernel.debug")),
			$configs
		);
	}
}
<?php

namespace Lukasbableck\ContaoFolderDownloadBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContaoFolderDownloadExtension extends Extension {
	public function load(array $configs, ContainerBuilder $container): void {
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
		$loader->load('services.yaml');
	}
}

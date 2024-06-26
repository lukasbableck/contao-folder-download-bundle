<?php

namespace Lukasbableck\ContaoFolderDownloadBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Lukasbableck\ContaoFolderDownloadBundle\ContaoFolderDownloadBundle;

class Plugin implements BundlePluginInterface {
	public function getBundles(ParserInterface $parser): array {
		return [BundleConfig::create(ContaoFolderDownloadBundle::class)->setLoadAfter([ContaoCoreBundle::class])];
	}
}

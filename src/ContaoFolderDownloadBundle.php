<?php

namespace Lukasbableck\ContaoFolderDownloadBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoFolderDownloadBundle extends Bundle {
	public function getPath(): string {
		return \dirname(__DIR__);
	}
}

<?php

namespace LukasBableck\ContaoFolderDownloadBundle\EventListener\DataContainer;

use Contao\Backend;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\FilesModel;
use Contao\Input;
use Contao\System;

#[AsHook('loadDataContainer')]
class AddOperationsListener extends Backend {
	public function __invoke(string $table): void {
		if ($table !== 'tl_files') {
			return;
		}

		$GLOBALS['TL_DCA'][$table]['list']['operations']['download'] = [
			'label' => &$GLOBALS['TL_LANG']['MSC']['download'],
			'href' => 'key=download',
			'icon' => 'bundles/contaoinstantindexing/icons/google.svg',
		];

		if (Input::get('key') === 'download' && Input::get('id')) {
			$file = FilesModel::findByPk(Input::get('id'));
			if ($file !== null) {
				if ($file->type == 'folder') {
					$projectDir = System::getContainer()->getParameter('kernel.project_dir');
					$zip = new \ZipArchive();
					$zip->open($projectDir.'/'.$file->path.'.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
					$files = new \RecursiveIteratorIterator(
						new \RecursiveDirectoryIterator($projectDir.'/'.$file->path),
						\RecursiveIteratorIterator::LEAVES_ONLY
					);
					foreach ($files as $name => $file) {
						if (!$file->isDir()) {
							$filePath = $file->getRealPath();
							$relativePath = substr($filePath, \strlen($projectDir.'/'.$file->path) + 1);
							$zip->addFile($filePath, $relativePath);
						}
					}
					$this->sendFileToBrowser($file->path.'.zip');
				} elseif ($file->type == 'file') {
					$this->sendFileToBrowser($file->path);
				}
			}
		}
	}
}

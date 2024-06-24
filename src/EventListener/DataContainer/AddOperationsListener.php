<?php

namespace Lukasbableck\ContaoFolderDownloadBundle\EventListener\DataContainer;

use Contao\Backend;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Exception\ResponseException;
use Contao\FilesModel;
use Contao\Input;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\String\UnicodeString;

#[AsHook('loadDataContainer')]
class AddOperationsListener extends Backend {
	public function __invoke(string $table): void {
		if ($table !== 'tl_files') {
			return;
		}

		$GLOBALS['TL_DCA'][$table]['list']['operations']['download'] = [
			'label' => &$GLOBALS['TL_LANG']['MSC']['download'],
			'href' => 'key=download',
			'icon' => 'bundles/contaofolderdownload/icons/download.svg',
		];

		if (Input::get('key') === 'download' && Input::get('id')) {
			$objFile = FilesModel::findByPath(Input::get('id'));
			if ($objFile !== null) {
				if ($objFile->type == 'folder') {
					$temp = tempnam(sys_get_temp_dir(), 'CFD');
					$zip = new \ZipArchive();
					$zip->open($temp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
					$files = new \RecursiveIteratorIterator(
						new \RecursiveDirectoryIterator($objFile->getAbsolutePath()),
						\RecursiveIteratorIterator::LEAVES_ONLY
					);
					foreach ($files as $name => $file) {
						if (!$file->isDir()) {
							$filePath = $file->getRealPath();
							$relativePath = substr($filePath, \strlen($file->getPath()) + 1);
							$zip->addFile($filePath, $relativePath);
						} else {
							$zip->addEmptyDir($file->getRealPath());
						}
					}
					$zip->close();

					$response = new BinaryFileResponse($temp);
					$response->setPrivate();
					$response->setAutoEtag();

					$response->setContentDisposition(
						ResponseHeaderBag::DISPOSITION_INLINE,
						$objFile->name.'.zip',
						(new UnicodeString($objFile->name.'.zip'))->ascii()->toString()
					);

					$response->headers->addCacheControlDirective('must-revalidate');
					$response->headers->set('Connection', 'close');
					$response->headers->set('Content-Type', 'application/zip');

					throw new ResponseException($response);
					unlink($temp);
				}
			}
		}
	}
}

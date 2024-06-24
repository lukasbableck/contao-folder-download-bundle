<?php

$GLOBALS['TL_DCA']['tl_files']['list']['operations']['downloadFolder'] = [
	'label' => &$GLOBALS['TL_LANG']['MSC']['downloadFolder'],
	'href' => 'key=download',
	'icon' => 'bundles/contaofolderdownload/icons/download.svg',
	'button_callback' => ['\Lukasbableck\ContaoFolderDownloadBundle\EventListener\DataContainer\ButtonListener', 'onButtonCallback']
];
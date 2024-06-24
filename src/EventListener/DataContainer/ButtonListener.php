<?php

namespace Lukasbableck\ContaoFolderDownloadBundle\EventListener\DataContainer;

use Contao\Backend;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;

class ButtonListener {
	public function onButtonCallback(
		array $row,
		?string $href,
		string $label,
		string $title,
		?string $icon,
		string $attributes,
		string $table,
		array $rootRecordIds,
		?array $childRecordIds,
		bool $circularReference,
		?string $previous,
		?string $next,
		DataContainer $dc
	): string {
		if($row['type'] !== 'folder') {
			return '';
		}
		return sprintf(
			'<a href="%s" title="%s"%s>%s</a> ',
			Backend::addToUrl($href . '&amp;id=' . $row['id']),
			StringUtil::specialchars($title),
			$attributes,
			Image::getHtml($icon, $label)
		);
	}
}

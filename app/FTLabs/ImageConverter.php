<?php
/**
 * Transforms images according to requirements given
 *
 * @codingstandard ftlabs-phpcs
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

namespace FTLabs;

class ImageConverter {
	private $tempDir = '/tmp/';

	function getConvertedImage(Image $img, ImageDefinition $req) {
		$args = [];

		if ($req->width || $req->height) {
			$args[] = '-resize';
			$args[] = ($req->width ?: '').'x'.($req->height ?: '');
		}

		$tempPath = tempnam($this->tempDir, 'ImageProxyConvert');

		$args[] = $img->getPath();
		$args[] = $tempPath;

		foreach ($args as &$arg) $arg = escapeshellarg($arg);

		exec("convert ".implode(" ", $args), $output, $ret);
		if ($ret) throw new Exception("ImageMagick failed (code $ret)", get_defined_vars());

		return new Image($tempPath);
	}
}


<?php
/**
 * Describes desired attributes of image
 *
 * @codingstandard ftlabs-phpcs
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

namespace FTLabs;

class ImageDefinition {

	public $source;
	public $width, $height;
	public $cropmode = 'cover', $bgcolor;
	public $opformat = 'auto', $quality = 'medium';

	function __construct(array $properties) {
		if (!empty($properties['width'])) {
			if (!is_int($properties['width']) && !ctype_digit($properties['width'])) throw new Exception("Width must be a positive integer, got ".$properties['width']);
			$this->width = $properties['width'];
		}

		if (!empty($properties['height'])) {
			if (!is_int($properties['height']) && !ctype_digit($properties['height'])) throw new Exception("Height must be a positive integer, got ".$properties['height']);
			$this->height = $properties['height'];
		}

		foreach ($properties as $opt => $val) {
			$this->{$opt} = $val;
		}
	}

	function getCacheKey() {
		return "W{$this->width}H{$this->height}C{$this->cropmode}B{$this->bgcolor}F{$this->opformat}Q{$this->quality}";
	}
}

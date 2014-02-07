<?php
/**
 * Image read from local filesystem
 *
 * @codingstandard ftlabs-phpcs
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

namespace FTLabs\Source;

class Filesystem extends Source {
	function __construct($path) {
		$this->path = $path;
	}

	function getImage() {
		return new \FTLabs\Image($this->path);
	}

	function getCanonicalURI() {
		return 'file://' + realpath($this->path);
	}
}

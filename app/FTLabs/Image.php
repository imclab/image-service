<?php
/**
 * Represents file with encoded pixels which can be stored on disk.
 *
 * @codingstandard ftlabs-phpcs
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

namespace FTLabs;

class Image {
	private $path;

	function __construct($path) {
		$this->path = $path;
	}

	function getData() {
		return file_get_contents($this->path);
	}

	function getPath() {
		return $this->path;
	}
}

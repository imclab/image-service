<?php
/**
 * Source to read images from (e.g. HTTP URL, disk, API)
 *
 * @codingstandard ftlabs-phpcs
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

namespace FTLabs\Source;

abstract class Source {
	abstract function getImage();
	abstract function getCanonicalURI();
}

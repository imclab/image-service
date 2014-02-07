<?php
/**
 * Grhmrhrmm
 *
 * @codingstandard ftlabs-phpcs
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

namespace FTLabs;

class ImageConverterTest extends \PHPUnit_Framework_TestCase {

	public function testResizingSquare() {
		$source = new Source\Filesystem(__DIR__.'/img/lenna.jpg');

		$this->assertInstanceOf(Image::class, $source->getImage());
		$this->assertFileExists($source->getImage()->getPath());

		list($sourceWidth, $sourceHeight) = getimagesize($source->getImage()->getPath());
		$this->assertEquals(256, $sourceWidth);
		$this->assertEquals(256, $sourceHeight);

		$req = new ImageDefinition([
			'width'=>25,
			'height'=>15,
		]);
		$this->assertEquals(25, $req->width);
		$this->assertEquals(15, $req->height);

		$conv = new ImageConverter();

		$image = $conv->getConvertedImage($source->getImage(), $req);
		$this->assertInstanceOf(Image::class, $image);

		$this->assertFileExists($image->getPath());

		list($width, $height) = getimagesize($image->getPath());

		// default mode is
		$this->assertEquals(15, $width, $image->getPath());
		$this->assertEquals(15, $height);
	}
}

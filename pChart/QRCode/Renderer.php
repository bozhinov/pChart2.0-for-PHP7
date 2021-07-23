<?php

namespace pChart\QRCode;

class Renderer
{
	private $encoded;
	private $options;

	function __construct($encoded, $opts)
	{
		$this->encoded = $encoded;
		$this->options = $opts;
	}

	public function createImage($myPicture)
	{
		$h = count($this->encoded);
		$imgH = $h + 2 * $this->options['margin'];

		$base_image = imagecreate($imgH, $imgH);

		// Extract options
		list($R, $G, $B) = $this->options['bgColor']->get();
		$bgColorAlloc = imagecolorallocate($base_image, $R, $G, $B);
		list($R, $G, $B) = $this->options['color']->get();
		$colorAlloc = imagecolorallocate($base_image, $R, $G, $B);

		imagefill($base_image, 0, 0, $bgColorAlloc);

		for($y = 0; $y < $h; $y++) {
			for($x = 0; $x < $h; $x++) {
				if ($this->encoded[$y][$x] & 1) {
					imagesetpixel($base_image, $x + $this->options['margin'], $y + $this->options['margin'], $colorAlloc);
				}
			}
		}

		$pixelPerPoint = min($this->options['size'], $imgH);
		$target_h = $imgH * $pixelPerPoint;

		$image = $myPicture->gettheImage();
		imagecopyresized($image, $base_image, $startX, $startY, 0, 0, $target_h, $target_h, $imgH, $imgH);
		imagedestroy($base_image);
	}
}
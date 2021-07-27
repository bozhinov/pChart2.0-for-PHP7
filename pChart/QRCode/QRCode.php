<?php
/*
 * PHP QR Code
 * Last update - 31.12.2019
 */

namespace pChart\QRCode;

use pChart\QRCode\Encoder\Encoder;
use pChart\pConf;
use pChart\pException;

class QRCode extends pConf {

	private $myPicture;

	function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
	}

	private function render($encoded)
	{
		$h = count($encoded);
		$margin = $this->get('margin');
		$imgH = $h + 2 * $margin;

		$base_image = imagecreate($imgH, $imgH);

		// Extract options
		list($R, $G, $B) = $this->get('bgColor')->get(); # ughhh
		$bgColorAlloc = imagecolorallocate($base_image, $R, $G, $B);
		list($R, $G, $B) = $this->get('color')->get();
		$colorAlloc = imagecolorallocate($base_image, $R, $G, $B);

		imagefill($base_image, 0, 0, $bgColorAlloc);

		for($y = 0; $y < $h; $y++) {
			for($x = 0; $x < $h; $x++) {
				if ($encoded[$y][$x] & 1) {
					imagesetpixel($base_image, $x + $margin, $y + $margin, $colorAlloc);
				}
			}
		}

		$pixelPerPoint = min($this->get('size'), $imgH);
		$target_h = $imgH * $pixelPerPoint;

		$image = $this->myPicture->gettheImage();
		imagecopyresized($image, $base_image, 0, 0, 0, 0, $target_h, $target_h, $imgH, $imgH);
		imagedestroy($base_image);
	}

	public function encode(string $text, array $opts = [])
	{
		$this->apply_user_options($opts);

		$this->setColor('color', 0);
		$this->setColor('bgColor', 255);

		$level = 0;
		if (isset($opts['level'])){
			switch(strtoupper($opts['level'])){
				case "L":
					#$level = 0;
					break;
				case "M":
					$level = 1;
					break;
				case "Q":
					$level = 2;
					break;
				case "H":
					$level = 3;
					break;
				default:
					throw pException::InvalidInput("Invalid value for level");
			}
		}

		$this->set_if_within_range_or_default('size', 0, 20, 3);
		$this->set_if_within_range_or_default('margin', 0, 20, 4);

		if($text == '\0' || $text == '') {
			throw pException::InvalidInput("Invalid value for text");
		}

		if (isset($opts['hint'])){
			switch(strtolower($opts['hint'])){
				case "numeric":
					$hint = 0;
					break;
				case "alphanumeric":
					$hint = 1;
					break;
				case "byte":
					$hint = 2;
					break;
				case "kanji":
					$hint = 3;
					break;
					default:
						throw pException::InvalidInput("Invalid value for hint");
			}
		} else {
			$hint = -1;
		}

		$encoded = (new Encoder($level))->encodeString($text, $hint);
		$this->render($encoded);
	}
}

<?php

namespace pChart\Barcodes;

use pChart\Barcodes\Encoders\QRCode\Encoder;

class QRCode extends pConf {

	private $myPicture;

	function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
	}

	private function render($encoded)
	{
		$h = count($encoded);

		$image = $this->myPicture->gettheImage();
		$opts = $this->options;

		$scale = $opts['scale'];
		$padding = $opts['padding'];
		$palette = $opts['palette'];
		$StartX = $opts['StartX'];
		$StartY = $opts['StartY'];

		// Apply scaling & aspect ratio
		$width = ($h * $scale) + $padding * 2;

		// Draw the background
		$bgColorAlloc = $this->myPicture->allocatepColor($palette['bgColor']);
		imagefilledrectangle($image, $StartX, $StartY, $StartX + $width, $StartY + $width, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($palette['color']);

		// Render the barcode
		for($y = 0; $y < $h; $y++) {
			for($x = 0; $x < $h; $x++) {
				if ($encoded[$y][$x] & 1) {
					imagefilledrectangle(
						$image,
						($x * $scale) + $padding + $StartX,
						($y * $scale) + $padding + $StartY,
						(($x + 1) * $scale - 1) + $padding + $StartX,
						(($y + 1) * $scale - 1) + $padding + $StartY,
						$colorAlloc
					);
				}
			}
		}
	}

	public function draw(string $text, array $opts = [])
	{
		$defaults = [
			'scale' => 3,
			'padding' => 4
		];
		$this->apply_user_options($opts, $defaults);

		$this->check_range('scale', 0, 20);
		$this->check_range('padding', 0, 20);

		$this->check_text_valid($text);

		$level = 0;
		if (isset($opts['level'])){
			$level = $this->return_key_if_found(strtoupper($opts['level']), ["L", "M", "Q", "H"]);
		}

		$hint = -1;
		if (isset($opts['hint'])){
			$hint = $this->return_key_if_found(strtolower($opts['hint']), ["numeric", "alphanumeric", "byte", "kanji"]);
		}

		$encoded = (new Encoder($level))->encodeString($text, $hint);
		$this->render($encoded);
	}
}

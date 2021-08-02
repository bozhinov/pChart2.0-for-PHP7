<?php

namespace pChart\Barcodes;

use pChart\Barcodes\Encoders\QRCode\Encoder;

define("BARCODES_QRCODE_LEVEL_L", 0);
define("BARCODES_QRCODE_LEVEL_M", 1);
define("BARCODES_QRCODE_LEVEL_Q", 2);
define("BARCODES_QRCODE_LEVEL_H", 3);

define("BARCODES_QRCODE_HINT_NUM", 0);
define("BARCODES_QRCODE_HINT_ALPHANUM", 1);
define("BARCODES_QRCODE_HINT_BYTE", 2);
define("BARCODES_QRCODE_HINT_KANJI", 3);

class QRCode extends pConf {

	private $myPicture;

	function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
	}

	private function render($pixelGrid)
	{
		$image = $this->myPicture->gettheImage();

		$scale = $this->options['scale'];
		$padding = $this->options['padding'];
		$StartX = $this->options['StartX'];
		$StartY = $this->options['StartY'];

		// Apply scaling & aspect ratio
		$h = count($pixelGrid);
		$width = ($h * $scale) + $padding * 2;

		// Draw the background
		$bgColorAlloc = $this->myPicture->allocatepColor($this->options['palette']['bgColor']);
		imagefilledrectangle($image, $StartX, $StartY, $StartX + $width, $StartY + $width, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($this->options['palette']['color']);

		// Render the barcode
		for($y = 0; $y < $h; $y++) {
			for($x = 0; $x < $h; $x++) {
				if ($pixelGrid[$y][$x] & 1) {
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
			'padding' => 4,
			'level' => BARCODES_QRCODE_LEVEL_L,
			'hint' => -1
		];
		$this->apply_user_options($opts, $defaults);

		$this->check_ranges([
			['scale', 1, 20],
			['padding', 0, 20],
			['level', 0, 3],
			['hint', -1, 3]
		]);

		$this->check_text_valid($text);

		$encoded = (new Encoder($this->options['level']))->encodeString($text, $this->options['hint']);
		$this->render($encoded);
	}
}

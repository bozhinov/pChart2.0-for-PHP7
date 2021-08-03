<?php

namespace pChart\Barcodes;

use pChart\Barcodes\Encoders\Aztec\Encoder;

define("BARCODES_AZTEC_HINT_BINARY", 0);
define("BARCODES_AZTEC_HINT_DYNAMIC", 1);

class Aztec extends pConf
{
	private $myPicture;

	public function __construct(\pChart\pDraw $myPicture)
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
		$width = ($h * $scale) + ($padding * 2);

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

	public function draw($data, array $opts = [])
	{
		$defaults = [
			'scale' => 3,
			'padding' => 4,
			'hint' => BARCODES_AZTEC_HINT_DYNAMIC,
			'eccPercent' => 33
		];
		$this->apply_user_options($opts, $defaults);

		$this->check_ranges([
			['scale', 1, 20],
			['padding', 0, 20],
			['eccPercent', 1, 100],
			['hint', 0, 1]
		]);

		$pixelGrid = (new Encoder())->encode($data, $this->options['eccPercent'], $this->options['hint']);

		$this->render($pixelGrid);
	}
}

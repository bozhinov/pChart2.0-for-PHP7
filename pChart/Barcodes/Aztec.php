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

		$width = count($pixelGrid);
		$scale = $this->options['scale'];
		$padding = $this->options['padding'];
		$StartX = $this->options['StartX'];
		$StartY = $this->options['StartY'];
		$size = ($width * $scale) + ($padding * 2);

		// Extract options
		$bgColorAlloc = $this->myPicture->allocatepColor($this->options['palette']['bgColor']);
		imagefilledrectangle($image, $StartX, $StartY, $StartX + $size, $StartY + $size, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($this->options['palette']['color']);

		// Render the code
		for ($x = 0; $x < $width; $x++) {
			for ($y = 0; $y < $width; $y++) {
				if (isset($pixelGrid[$x][$y])){
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

		$this->check_range('hint', 0, 1);
		$this->check_range('eccPercent', 1, 100);
		$this->check_range('scale', 1, 20);
		$this->check_range('padding', 0, 20);

		$pixelGrid = (new Encoder())->encode($data, $this->options['eccPercent'], $this->options['hint']);

		$this->render($pixelGrid);
	}
}

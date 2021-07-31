<?php

namespace pChart\Barcodes;

use pChart\Barcodes\Encoders\Aztec\Encoder;

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
		$opts = $this->options;

		$width = count($pixelGrid);
		$scale = $opts['scale'];
		$padding = $opts['padding'];
		$StartX = $opts['StartX'];
		$StartY = $opts['StartY'];
		$size = ($width * $scale) + ($padding * 2);

		// Extract options
		$bgColorAlloc = $this->myPicture->allocatepColor($opts['palette']['bgColor']);
		imagefilledrectangle($image, $StartX, $StartY, $StartX + $size, $StartY + $size, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($opts['palette']['color']);

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
			'hint' => 'dynamic',
			'eccPercent' => 33
		];
		$this->apply_user_options($opts, $defaults);

		$this->check_valid('hint', ["binary", "dynamic"]);
		$this->check_range('eccPercent', 1, 100);
		$this->check_range('scale', 1, 20);
		$this->check_range('padding', 0, 20);

		$pixelGrid = (new Encoder())->encode($data, $this->options['eccPercent'], $this->options['hint']);

		$this->render($pixelGrid);
	}
}

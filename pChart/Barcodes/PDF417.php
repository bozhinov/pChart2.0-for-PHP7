<?php

namespace pChart\Barcodes;

use pChart\Barcodes\Encoders\PDF417\Encoder;

class PDF417 extends pConf
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

		$padding = $opts['padding'];
		$scaleX = $opts['scale'];
		$scaleY = $scaleX * $opts['ratio'];
		$palette = $opts['palette'];
		$StartX = $opts['StartX'];
		$StartY = $opts['StartY'];

		// Apply scaling & aspect ratio
		$width = (count($pixelGrid[0]) * $scaleX) + $padding * 2;
		$height = (count($pixelGrid) * $scaleY) + $padding * 2;

		// Draw the background
		$bgColorAlloc = $this->myPicture->allocatepColor($palette['bgColor']);
		imagefilledrectangle($image, $StartX, $StartY, $StartX + $width, $StartY + $height, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($palette['color']);

		// Render the barcode
		foreach ($pixelGrid as $y => $row) {
			foreach ($row as $x => $value) {
				if ($value) {
					imagefilledrectangle(
						$image,
						($x * $scaleX) + $padding + $StartX,
						($y * $scaleY) + $padding + $StartY,
						(($x + 1) * $scaleX - 1) + $padding + $StartX,
						(($y + 1) * $scaleY - 1) + $padding + $StartY,
						$colorAlloc
					);
				}
			}
		}
	}

	public function draw($data, array $opts = [])
	{
		$defaults = [
			'columns' => 6,
			'scale' => 3,
			'ratio' => 3,
			'padding' => 20,
			'securityLevel' => 2,
			'hint' => 'none'
		];
		$this->apply_user_options($opts, $defaults);

		$this->check_range('columns', 1, 30);
		$this->check_range('scale', 1, 20);
		$this->check_range('ratio', 1, 10);
		$this->check_range('padding', 0, 20);
		$this->check_range('securityLevel', 0, 8);
		$this->check_valid('hint', ["binary", "numbers", "text", "none"]);

		$securityLevel = $this->get('securityLevel');
		$columns = $this->get('columns');
		$hint = $this->get('hint');

		$pixelGrid = (new Encoder($columns, $securityLevel, $hint))->encodeData($data);
		$this->render($pixelGrid);
	}
}

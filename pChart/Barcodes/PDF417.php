<?php

namespace pChart\Barcodes;

use pChart\Barcodes\Encoders\PDF417\Encoder;
use pChart\pConf;

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
		$padding = $this->get('padding');
		$scaleX = $this->get('scale');
		$scaleY = $scaleX * $this->get('ratio');
		$StartX = $this->get('StartX');
		$StartY = $this->get('StartY');

		// Apply scaling & aspect ratio
		$width = (count($pixelGrid[0]) * $scaleX) + $padding * 2;
		$height = (count($pixelGrid) * $scaleY) + $padding * 2;

		// Draw the background
		$bgColorAlloc = $this->myPicture->allocatepColor($this->get('bgColor'));
		imagefilledrectangle($image, $StartX, $StartY, $StartX + $width, $StartY + $height, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($this->get('color'));

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
		$this->apply_user_options($opts);

		$columns = $this->return_if_within_range_or_default('columns', 1, 30, 6);
		$this->set_if_within_range_or_default('scale', 1, 20, 3);
		$this->set_if_within_range_or_default('ratio', 1, 10, 3);
		$this->set_if_within_range_or_default('padding', 0, 50, 20);
		$securityLevel = $this->return_if_within_range_or_default('securityLevel', 0, 8, 2);
		$hint = $this->return_if_match_or_default('hint',["binary", "numbers", "text", "none"], 'none');

		$pixelGrid = (new Encoder($columns, $securityLevel, $hint))->encodeData($data);
		$this->render($pixelGrid);
	}
}

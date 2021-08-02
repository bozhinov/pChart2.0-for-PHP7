<?php

namespace pChart\Barcodes;

use pChart\Barcodes\Encoders\PDF417\Encoder;
use pChart\Barcodes\Encoders\PDF417\EncoderByte;
use pChart\Barcodes\Encoders\PDF417\EncoderText;
use pChart\Barcodes\Encoders\PDF417\EncoderNumber;

define("BARCODES_PDF417_HINT_NUMBERS", 0);
define("BARCODES_PDF417_HINT_TEXT", 1);
define("BARCODES_PDF417_HINT_BINARY", 2);
define("BARCODES_PDF417_HINT_NONE", 3);

class PDF417 extends pConf
{
	private $myPicture;
	private $encoders = [];

	public function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
		$this->encoders = [
			new EncoderNumber(),
			new EncoderText(),
			new EncoderByte()
		];
	}

	private function render($pixelGrid)
	{
		$image = $this->myPicture->gettheImage();
		$opts = $this->options;

		$padding = $opts['padding'];
		$scaleX = $opts['scale'];
		$scaleY = $scaleX * $opts['ratio'];
		$StartX = $opts['StartX'];
		$StartY = $opts['StartY'];

		// Apply scaling & aspect ratio
		$width = (count($pixelGrid[0]) * $scaleX) + $padding * 2;
		$height = (count($pixelGrid) * $scaleY) + $padding * 2;

		// Draw the background
		$bgColorAlloc = $this->myPicture->allocatepColor($opts['palette']['bgColor']);
		imagefilledrectangle($image, $StartX, $StartY, $StartX + $width, $StartY + $height, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($opts['palette']['color']);

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
			'hint' => BARCODES_PDF417_HINT_NONE
		];
		$this->apply_user_options($opts, $defaults);

		$this->check_ranges([
			['columns', 1, 30],
			['scale', 1, 20],
			['ratio', 1, 10],
			['padding', 0, 20],
			['securityLevel', 0, 8],
			['hint', 0, 3]
		]);

		$pixelGrid = (new Encoder($this->options))->encodeData($data, $this->encoders);
		$this->render($pixelGrid);
	}
}

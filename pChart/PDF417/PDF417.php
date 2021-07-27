<?php

namespace pChart\PDF417;

use pChart\PDF417\Encoder\Encoder;
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

		$width = count($pixelGrid[0]);
		$height = count($pixelGrid);

		$scaleX = $this->get('scale');
		$scaleY = $scaleX * $this->get('ratio');

		// Apply scaling & aspect ratio
		$width = ($width * $scaleX) + $padding * 2;
		$height = ($height * $scaleY) + $padding * 2;

		// Extract options
		$bgColorAlloc = $this->myPicture->allocatepColor($this->get('bgColor'));
		imagefill($image, 0, 0, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($this->get('color'));

		// Render the barcode
		foreach ($pixelGrid as $y => $row) {
			foreach ($row as $x => $value) {
				if ($value) {
					imagefilledrectangle(
						$image,
						($x * $scaleX) + $padding,
						($y * $scaleY) + $padding,
						(($x + 1) * $scaleX - 1) + $padding,
						(($y + 1) * $scaleY - 1) + $padding,
						$colorAlloc
					);
				}
			}
		}
	}

	public function encode($data, array $opts = [])
	{
		$this->apply_user_options($opts);

		$this->setColor('color', 0);
		$this->setColor('bgColor', 255);
		/**
		* Number of data columns in the bar code.
		*
		* The total number of columns will be greater due to adding start, stop,
		* left and right columns.
		*/
		$columns = $this->return_if_within_range_or_default('columns', 1, 30, 6);

		/**
		* Can be used to force binary encoding. This may reduce size of the
		* barcode if the data contains many encoder changes, such as when
		* encoding a compressed file.
		*/
		$this->set_if_within_range_or_default('scale', 1, 20, 3);
		$this->set_if_within_range_or_default('ratio', 1, 10, 3);
		$this->set_if_within_range_or_default('padding', 0, 50, 20);
		$securityLevel = $this->return_if_within_range_or_default('securityLevel', 0, 8, 2);
		$hint = $this->return_if_match_or_default('hint',["binary", "numbers", "text", "none"], 'none');

		$pixelGrid = (new Encoder($columns, $securityLevel, $hint))->encodeData($data);
		$this->render($pixelGrid);
	}
}

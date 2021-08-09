<?php

namespace pChart\Barcodes;

define("BARCODES_PDF417_HINT_NUMBERS", 0);
define("BARCODES_PDF417_HINT_TEXT", 1);
define("BARCODES_PDF417_HINT_BINARY", 2);
define("BARCODES_PDF417_HINT_NONE", 3);

class PDF417 extends pConf
{
	private $myPicture;

	public function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
	}

	public function draw($data, int $x, int y, array $opts = [])
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

		$pixelGrid = (new Encoders\PDF417\Encoder())->encode($data, $this->options);
		$this->myPicture->drawBarcodeFromGrid($pixelGrid, $x, $y, $this->options);
	}
}

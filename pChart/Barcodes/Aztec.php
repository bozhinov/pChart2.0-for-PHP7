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

	public function draw($data, int $x, int $y, array $opts = [])
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
			['hint', 0, 1],
			['eccPercent', 1, 100]
		]);

		$pixelGrid = (new Encoder())->encode($data, $this->options);
		$this->myPicture->drawBarcodeFromGrid($pixelGrid, $x, $y, $this->options);
	}
}

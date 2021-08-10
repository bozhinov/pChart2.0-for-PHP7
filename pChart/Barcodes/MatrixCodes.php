<?php

namespace pChart\Barcodes;

use pChart\pException;

define("BARCODES_DTMX_PATTERN_SQUARE", 0);
define("BARCODES_DTMX_PATTERN_RECT", 1);

class MatrixCodes extends pConf {

	private $myPicture;
	private $encoder;

	function __construct(\pChart\pDraw $pChartObject)
	{
		$this->myPicture = $pChartObject;
		$this->encoder = new Encoders\DMTX\Encoder();
	}

	private function parse_opts($opts)
	{
		$defaults = [
			'scale' => 4,
			'padding' => 4,
			'pattern' => BARCODES_DTMX_PATTERN_SQUARE,
			'GS-1' => false
		];

		$this->apply_user_options($opts, $defaults);

		$this->check_ranges([
			['scale', 1, 20],
			['padding', 0, 20],
			['pattern', 0, 1]
		]);
	}

	public function draw($data, int $x, int $y, array $opts = [])
	{
		$this->parse_opts($opts);
		$code = $this->encoder->encode($data, $this->options);
		$this->myPicture->drawBarcodeFromGrid($code, $x, $y, $this->options);
	}
}
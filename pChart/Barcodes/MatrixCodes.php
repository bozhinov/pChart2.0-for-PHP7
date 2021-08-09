<?php

namespace pChart\Barcodes;

use pChart\pException;

class MatrixCodes extends pConf {

	private $myPicture;

	function __construct(\pChart\pDraw $pChartObject)
	{
		$this->myPicture = $pChartObject;
		$this->encoder = new Encoders\DMTX\Encoder();
	}

	private function parse_opts($opts)
	{
		$defaults = [
			'scale' => 4,
			'padding' => 4
		];

		$this->apply_user_options($opts, $defaults);

		$this->check_ranges([
			['scale', 1, 20],
			['padding', 0, 20]
		]);
	}

	public function draw($data, int $x, int $y, string $symbology, array $opts = [])
	{
		switch ($symbology) {
			case 'dmtx':
			case 'dmtxs':
				$code = $this->encoder->encode($data, false, false);
				break;
			case 'dmtxr':
				$code = $this->encoder->encode($data, true,  false);
				break;
			case 'dmtxgs1':
			case 'dmtxsgs1':
				$code = $this->encoder->encode($data, false, true);
				break;
			case 'dmtxrgs1': 
				$code = $this->encoder->encode($data, true,  true);
				break;
			default: throw pException::InvalidInput("Unknown encode method - ".$symbology);
		}

		$this->parse_opts($opts);
		$this->myPicture->drawBarcodeFromGrid($code, $x, $y, $this->options);
	}
}

<?php 

/*
pBarcodes2D - Wrapper for all the 2D barcode libs
             (QRCode, PDF417, Aztec, DTMX)
Version     : 2.4.0-dev
Made by     : Momchil Bozhinov
Last Update : 09/08/2021
*/

namespace pChart;

define("BARCODES_PDF417_HINT_NUMBERS", 0);
define("BARCODES_PDF417_HINT_TEXT", 1);
define("BARCODES_PDF417_HINT_BINARY", 2);
define("BARCODES_PDF417_HINT_NONE", 3);

define("BARCODES_AZTEC_HINT_BINARY", 0);
define("BARCODES_AZTEC_HINT_DYNAMIC", 1);

define("BARCODES_QRCODE_LEVEL_L", 0);
define("BARCODES_QRCODE_LEVEL_M", 1);
define("BARCODES_QRCODE_LEVEL_Q", 2);
define("BARCODES_QRCODE_LEVEL_H", 3);

define("BARCODES_QRCODE_HINT_NUM", 0);
define("BARCODES_QRCODE_HINT_ALPHANUM", 1);
define("BARCODES_QRCODE_HINT_BYTE", 2);
define("BARCODES_QRCODE_HINT_KANJI", 3);

class pBarcodes2D extends \pChart\Barcodes\pConf {

	private $encoder;
	private $engine;
	private $myPicture;

	public function __construct(int $encoder, \pChart\pDraw $myPicture)
	{
		$this->encoder = $encoder;
		$this->myPicture = $myPicture;

		switch($encoder)
		{
			case BARCODES_ENGINE_AZTEC:
				$this->engine = new Barcodes\Encoders\Aztec\Encoder();
				break;
			default: throw pException::InvalidInput("Unknown encode engine");
		}
	}

	private function parse_opts_aztec($opts)
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
	}

	public function draw($data, int $x = 10, int $y = 10, array $opts = [])
	{
		switch($this->encoder)
		{
			case BARCODES_ENGINE_AZTEC:
				$this->parse_opts_aztec($opts);
				break;
		}

		$pixelGrid = $this->engine->encode($data, $this->options);
		$this->myPicture->drawBarcodeFromGrid($pixelGrid, $x, $y, $this->options);
	}
}
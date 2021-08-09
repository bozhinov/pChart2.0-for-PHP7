<?php

namespace pChart\Barcodes;

use pChart\Barcodes\Encoders\QRCode\Encoder;

define("BARCODES_QRCODE_LEVEL_L", 0);
define("BARCODES_QRCODE_LEVEL_M", 1);
define("BARCODES_QRCODE_LEVEL_Q", 2);
define("BARCODES_QRCODE_LEVEL_H", 3);

define("BARCODES_QRCODE_HINT_NUM", 0);
define("BARCODES_QRCODE_HINT_ALPHANUM", 1);
define("BARCODES_QRCODE_HINT_BYTE", 2);
define("BARCODES_QRCODE_HINT_KANJI", 3);

class QRCode extends pConf {

	private $myPicture;

	public function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
	}

	public function draw($data, array $opts = [])
	{
		$defaults = [
			'scale' => 3,
			'padding' => 4,
			'level' => BARCODES_QRCODE_LEVEL_L,
			'hint' => -1,
			'random_mask' => 0
		];
		$this->apply_user_options($opts, $defaults);

		$this->check_ranges([
			['scale', 1, 20],
			['padding', 0, 20],
			['level', 0, 3],
			['hint', -1, 3],
			['random_mask', 0, 8]
		]);

		$this->check_text_valid($data);

		$pixelGrid = (new Encoder())->encode($data, $this->options);
		$this->myPicture->drawBarcodeFromGrid($pixelGrid, $this->options);
	}
}

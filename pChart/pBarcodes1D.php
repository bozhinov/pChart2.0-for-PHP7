<?php

namespace pChart;

class pBarcodes1D extends Barcodes\pConf {

	private $encoder;
	private $engine;
	private $myPicture;

	public function __construct(string $encoder, pDraw $myPicture)
	{
		$this->encoder = $encoder;
		$this->myPicture = $myPicture;

		/* Available engines ->
		BARCODES_ENGINE_UPC
		BARCODES_ENGINE_CODE39
		BARCODES_ENGINE_CODE93
		BARCODES_ENGINE_CODE128
		BARCODES_ENGINE_CODABAR
		BARCODES_ENGINE_ITF
		*/

		try {
			$class = "pChart\\Barcodes\\Linear\\$encoder";
			$this->engine = new $class;
		} catch (\Throwable $e) {
			throw pException::InvalidInput("Unknown encoding engine");
		}
	}

	private function parse_opts($opts)
	{
		$defaults = [
			'mode' => "",
			'GS-1' => false,
			'scale' => 1,
			'width' => NULL,
			'height' => NULL,
			'widths' => [
				'QuietArea' 	=> 1,
				'NarrowModules' => 1,
				'WideModules' 	=> 3,
				'NarrowSpace' 	=> 1
				],
			"label" => [
					'height' => 10,
					'size' => 1,
					'color' => NULL,
					'skip' => FALSE,
					'ttf' => NULL,
					'offset' => 0
				]
		];

		$this->apply_user_options($opts, $defaults);

		$this->check_ranges([
			['scale', 1, 20]
		]);

		if (is_null($this->options['label']['color']) && (!$this->options['label']['skip'])){
			$this->options['label']['color'] = $this->options['palette']['color'];
		}
	}

	public function draw($data, int $x, int $y, array $opts = [])
	{
		/*
		BARCODES_ENGINE_UPC : 
			'upca' = ['mode' => 'upca']; 
			'upce' = ['mode' => 'upce']; 
			'ean13nopad' = ['mode' => 'ean13nopad']; 
			'ean13pad'   = ['mode' => 'ean13pad'];
			'ean13' = ['mode' => 'ean13'];
			'ean8'  = ['mode' => 'ean8']; 
		
		BARCODES_ENGINE_CODE39
			'code39' = ['mode' => 'data']; 
			'code39ascii' = ['mode' => 'ascii'];
		
		BARCODES_ENGINE_CODE93
			'code93' = ['mode' => 'data']; 
			'code93ascii' = ['mode' => 'ascii'];
		
		BARCODES_ENGINE_CODE128
			'code128' =  ['GS-1' => false, 'mode' => ""]; 
			'code128a' = ['GS-1' => false, 'mode' => "a"];
			'code128b' = ['GS-1' => false, 'mode' => "b"];
			'code128c' = ['GS-1' => false, 'mode' => "c"];
			'code128ac' =['GS-1' => false, 'mode' => "ac"];
			'code128bc' =['GS-1' => false, 'mode' => "bc"];
			'GS1-128' =  ['GS-1' => true,  'mode' => ""];
			'GS1-128a' = ['GS-1' => true,  'mode' => "a"];
			'GS1-128b' = ['GS-1' => true,  'mode' => "b"];
			'GS1-128c' = ['GS-1' => true,  'mode' => "c"];
			'GS1-128ac' =['GS-1' => true,  'mode' => "ac"];
			'GS1-128bc' =['GS-1' => true,  'mode' => "bc"];
		
		BARCODES_ENGINE_CODABAR
			'codabar' = [];
		
		BARCODES_ENGINE_ITF
			'itf' = []
			'itf14' = []
		*/

		$this->parse_opts($opts);
		$code = $this->engine->encode($data, $this->options);
		$this->myPicture->draw1DBarcode($code, $x, $y, $this->options);
	}
}

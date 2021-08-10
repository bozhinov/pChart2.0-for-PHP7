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
			$class = "pChart\\Barcodes\\Encoders\\Linear\\$encoder";
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

		if (is_null($this->options['label']['color']) && (!$this->options['label']['skip'])){
			$this->options['label']['color'] = $this->options['palette']['color'];
		}
	}

	public function render($code, $x, $y)
	{
		$width = 0;
		$widths = array_values($this->options['widths']);
		foreach ($code as $block){
			foreach ($block['m'] as $module){
				$width += $module[1] * $widths[$module[2]];
			}
		}

		$label = $this->options['label'];
		$lsize = $label['size'];

		$w = (!is_null($this->options['width']))  ? intval($this->options['width'])  : intval(ceil($width * $this->options['scale']));
		$h = (!is_null($this->options['height'])) ? intval($this->options['height']) : intval(ceil(80 * $this->options['scale']));

		if ($width > 0) {
			$scale = $w / $width;
			$scale = ($scale > 1) ? $scale : 1;
		} else {
			$scale = 1;
		}

		$image = $this->myPicture->gettheImage();
		$palette = array_values($this->options['palette']);

		# pre-allocate colors
		foreach($palette as $id => $color) {
			$palette[$id] = $this->myPicture->allocatepColor($color);
		}

		if ($label['skip'] != TRUE) {
			$label_color = $this->myPicture->allocatepColor($label['color']);
		}

		foreach ($code as $block) {

			if (isset($block['l'])) {
				$ly = (isset($block['l'][1]) ? (float)$block['l'][1] : 1);
				$my = round($y + min($h, $h + ($ly - 1) * intval($label['height'])));
			} else {
				$my = $y + $h;
			}

			$mx = $x;

			foreach ($block['m'] as $module) {
				$mw = $mx + $module[1] * $widths[$module[2]] * $scale;
				imagefilledrectangle($image, intval($mx), $y, intval($mw - 1), intval($my - 1), $palette[$module[0]]);
				$mx = $mw;
			}

			if ($label['skip'] != TRUE) {
				if (isset($block['l'])) {
					$text = $block['l'][0];
					$lx = (isset($block['l'][2]) ? (float)$block['l'][2] : 0.5);
					$lx = ($x + ($mx - $x) * $lx);
					$lw = imagefontwidth($lsize) * strlen($text);
					$lx = intval(round($lx - $lw / 2));
					$ly = ($y + $h + $ly * $label['height']);
					$ly = intval(round($ly - imagefontheight($lsize)));
					if (!is_null($label['ttf'])) {
						$ly +=($lsize*2) + $label['offset'];
						imagettftext($image, $lsize, 0, $lx, $ly, $label_color, realpath($label['ttf']), $text);
					} else {
						imagestring($image,  $lsize, $lx, $ly, $text, $label_color);
					}
				}
			}

			$x = $mx;
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
		$this->render($code, $x, $y);
	}
}

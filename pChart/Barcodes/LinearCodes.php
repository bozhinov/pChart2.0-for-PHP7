<?php

namespace pChart\Barcodes;

use pChart\pColor;
use pChart\pException;

class LinearCodes {

	private $myPicture;
	private $options = ['StartX' => 0, 'StartY' => 0];

	function __construct(\pChart\pDraw $pChartObject)
	{
		$this->myPicture = $pChartObject;
	}

	public function set_start_position(int $x, int $y)
	{
		$this->options['StartX'] = $x;
		$this->options['StartY'] = $y;
	}

	private function parse_opts($opts)
	{
		$config = [];
		$config["label"] = ['Height' => 10, 'Size' => 1, 'Color' => new pColor(0), 'Skip' => FALSE, 'TTF' => NULL, 'Offset' => 0];
		if (isset($opts['label'])){
			$config["label"] = array_replace($config["label"], $opts['label']);
		}
		# pre-allocate colors
		$config['label']['Color'] = $this->myPicture->allocatepColor($config['label']['Color']);

		$config["palette"] = [
			0 => new pColor(255), // CS - Color of spaces
			1 => new pColor(0) 	// CM - Color of modules
		];

		if (isset($opts['palette'])){
			$config["palette"] = array_replace($config["palette"], $opts['palette']);
		}

		# pre-allocate colors
		foreach($config['palette'] as $id => $color) {
			if ($color instanceof \pChart\pColor){
				$config['palette'][$id] = $this->myPicture->allocatepColor($color);
			}
		}

		// widths
		$config['widths'] = [
			'QuietArea' 	=> 1,
			'NarrowModules' => 1,
			'WideModules' 	=> 3,
			'NarrowSpace' 	=> 1
		];

		if (isset($opts['widths'])){
			$config['widths'] = array_replace($config['widths'], $opts['widths']);
		}

		// scale
		$config['scale'] = (isset($opts['scale'])) ? (float)$opts['scale'] : 1;

		// dimentions
		$config['Width']  = (isset($opts['Width'])  ? (int)$opts['Width']  : NULL);
		$config['Height'] = (isset($opts['Height']) ? (int)$opts['Height'] : NULL);

		return $config;
	}

	public function render($config, $code)
	{
		# calculate_size
		$width = 0;
		$widths = array_values($config['widths']);
		foreach ($code as $block){
			foreach ($block['m'] as $module){
				$width += $module[1] * $widths[$module[2]];
			}
		}

		$x = $config['StartX'];
		$y = $config['StartY'];
		$w = (!is_null($config['Width']))  ? $config['Width']  : intval(ceil($width * $config['scale']));
		$h = (!is_null($config['Height'])) ? $config['Height'] : intval(ceil(80 * $config['scale']));

		$lsize = $config['label']['Size'];

		if ($width > 0) {
			$scale = $w / $width;
			$scale = (($scale > 1) ? floor($scale) : 1);
		} else {
			$scale = 1;
		}
		
		$image = $this->myPicture->gettheImage();

		foreach ($code as $block) {

			if (isset($block['l'])) {
				$ly = (isset($block['l'][1]) ? (float)$block['l'][1] : 1);
				$my = round($y + min($h, $h + ($ly - 1) * $config['label']['Height']));
			} else {
				$my = $y + $h;
			}

			$mx = $x;

			foreach ($block['m'] as $module) {
				$mw = $mx + $module[1] * $widths[$module[2]] * $scale;
				imagefilledrectangle($image, $mx, $y, intval($mw - 1), intval($my - 1), $config['palette'][$module[0]]);
				$mx = $mw;
			}

			if ($config['label']['Skip'] != TRUE) {
				if (isset($block['l'])) {
					$text = $block['l'][0];
					$lx = (isset($block['l'][2]) ? (float)$block['l'][2] : 0.5);
					$lx = ($x + ($mx - $x) * $lx);
					$lw = imagefontwidth($lsize) * strlen($text);
					$lx = intval(round($lx - $lw / 2));
					$ly = ($y + $h + $ly * $config['label']['Height']);
					$ly = intval(round($ly - imagefontheight($lsize)));
					if (!is_null($config['label']['TTF'])) {
						$ly +=($lsize*2) + $config['label']['Offset'];
						imagettftext($image, $lsize, 0, $lx, $ly, $config['label']['Color'], realpath($config['label']['TTF']), $text);
					} else {
						imagestring($image,  $lsize, $lx, $ly, $text, $config['label']['Color']);
					}
				}
			}

			$x = $mx;
		}
	}

	public function draw($data, string $symbology, array $opts = [])
	{
		switch ($symbology) {
			case 'upca'       : $code = (new Encoders\UPC)->upc_a_encode($data); break;
			case 'upce'       : $code = (new Encoders\UPC)->upc_e_encode($data); break;
			case 'ean13nopad' : $code = (new Encoders\UPC)->ean_13_encode($data, ' '); break;
			case 'ean13pad'   :
			case 'ean13'      :
				$code = (new Encoders\UPC)->ean_13_encode($data, '>');
				break;
			case 'ean8'       : $code = (new Encoders\UPC)->ean_8_encode($data); break;
			case 'code39'     : $code = (new Encoders\Codes)->code_39_encode($data); break;
			case 'code39ascii': $code = (new Encoders\Codes)->code_39_ascii_encode($data); break;
			case 'code93'     : $code = (new Encoders\Codes)->code_93_encode($data); break;
			case 'code93ascii': $code = (new Encoders\Codes)->code_93_ascii_encode($data); break;
			case 'code128'    : $code = (new Encoders\Codes)->code_128_encode($data, 0, false); break;
			case 'code128a'   : $code = (new Encoders\Codes)->code_128_encode($data, 1, false); break;
			case 'code128b'   : $code = (new Encoders\Codes)->code_128_encode($data, 2, false); break;
			case 'code128c'   : $code = (new Encoders\Codes)->code_128_encode($data, 3, false); break;
			case 'code128ac'  : $code = (new Encoders\Codes)->code_128_encode($data,-1, false); break;
			case 'code128bc'  : $code = (new Encoders\Codes)->code_128_encode($data,-2, false); break;
			case 'ean128'     : $code = (new Encoders\Codes)->code_128_encode($data, 0, true); break;
			case 'ean128a'    : $code = (new Encoders\Codes)->code_128_encode($data, 1, true); break;
			case 'ean128b'    : $code = (new Encoders\Codes)->code_128_encode($data, 2, true); break;
			case 'ean128c'    : $code = (new Encoders\Codes)->code_128_encode($data, 3, true); break;
			case 'ean128ac'   : $code = (new Encoders\Codes)->code_128_encode($data,-1, true); break;
			case 'ean128bc'   : $code = (new Encoders\Codes)->code_128_encode($data,-2, true); break;
			case 'codabar'    : $code = Encoders\Codabar::codabar_encode($data); break;
			case 'itf'        :
			case 'itf14'      :
				$code = Encoders\ITF::itf_encode($data);
				break;
			default: throw pException::InvalidInput("Unknown encode method - ".$symbology);
		}

		$opts = $this->options + $this->parse_opts($opts);
		$this->render($opts, $code);
	}
}

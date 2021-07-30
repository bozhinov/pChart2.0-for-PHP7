<?php

namespace pChart\Barcodes;

use pChart\pException;

class LinearCodes extends pConf {

	private $myPicture;

	function __construct(\pChart\pDraw $pChartObject)
	{
		$this->myPicture = $pChartObject;
	}

	private function parse_opts($opts)
	{
		$defaults = [
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
			#'palette' => [
			#	0 => new pColor(255), // CS - Color of spaces
			#	1 => new pColor(0) 	// CM - Color of modules
			#]
		];

		$this->apply_user_options($opts, $defaults);

		if (is_null($this->options['label']['color'])){
			$this->options['label']['color'] = $this->options['palette']['color'];
		}
	}

	public function render($code)
	{
		$opts = $this->options;

		# calculate_size
		$width = 0;
		$widths = array_values($opts['widths']);
		foreach ($code as $block){
			foreach ($block['m'] as $module){
				$width += $module[1] * $widths[$module[2]];
			}
		}

		$label = $opts['label'];
		$scale = (float)$opts['scale'];

		$x = intval($opts['StartX']);
		$y = intval($opts['StartY']);
		$w = (!is_null($opts['width']))  ? intval($opts['width'])  : intval(ceil($width * $scale));
		$h = (!is_null($opts['height'])) ? intval($opts['height']) : intval(ceil(80 * $scale));

		$lsize = $label['size'];

		if ($width > 0) {
			$scale = $w / $width;
			$scale = (($scale > 1) ? floor($scale) : 1);
		} else {
			$scale = 1;
		}

		$image = $this->myPicture->gettheImage();
		$palette = array_values($opts['palette']);

		# pre-allocate colors
		foreach($palette as $id => $color) {
			$palette[$id] = $this->myPicture->allocatepColor($color);
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
					$label_color = $this->myPicture->allocatepColor($label['color']);
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

		$this->parse_opts($opts);
		$this->render($code);
	}
}

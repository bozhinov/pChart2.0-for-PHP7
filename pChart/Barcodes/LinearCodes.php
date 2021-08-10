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

		if (is_null($this->options['label']['color']) && (!$this->options['label']['skip'])){
			$this->options['label']['color'] = $this->options['palette']['color'];
		}
	}

	public function render($code)
	{
		# calculate_size
		$width = 0;
		$widths = array_values($this->options['widths']);
		foreach ($code as $block){
			foreach ($block['m'] as $module){
				$width += $module[1] * $widths[$module[2]];
			}
		}

		$label = $this->options['label'];
		$lsize = $label['size'];

		$x = intval($this->options['StartX']);
		$y = intval($this->options['StartY']);
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

	public function draw($data, string $symbology, array $opts = [])
	{
		switch ($symbology) {
			case 'upca'       : 
				$code = (new Encoders\UPC)->upc_a_encode($data);
				break;
			case 'upce'       : 
				$code = (new Encoders\UPC)->upc_e_encode($data);
				break;
			case 'ean13nopad' : 
				$code = (new Encoders\UPC)->ean_13_encode($data, ' ');
				break;
			case 'ean13pad'   :
			case 'ean13'      :
				$code = (new Encoders\UPC)->ean_13_encode($data, '>');
				break;
			case 'ean8'       : 
				$code = (new Encoders\UPC)->ean_8_encode($data);
				break;
			case 'code39'     :
				$options = ['mode' => 'data']; 
				$code = (new Encoders\Code39)->encode($data, $options);
				break;
			case 'code39ascii':
				$options = ['mode' => 'ascii'];
				$code = (new Encoders\Code39)->encode($data, $options);
				break;
			case 'code93'     :
				$options = ['mode' => 'data']; 
				$code = (new Encoders\Code93)->encode($data, $options);
				break;
			case 'code93ascii':
				$options = ['mode' => 'ascii']; 
				$code = (new Encoders\Code93)->encode($data, $options);
				break;
			case 'code128'    : $code = (new Encoders\Code128)->encode($data, 0, false); break;
			case 'code128a'   : $code = (new Encoders\Code128)->encode($data, 1, false); break;
			case 'code128b'   : $code = (new Encoders\Code128)->encode($data, 2, false); break;
			case 'code128c'   : $code = (new Encoders\Code128)->encode($data, 3, false); break;
			case 'code128ac'  : $code = (new Encoders\Code128)->encode($data,-1, false); break;
			case 'code128bc'  : $code = (new Encoders\Code128)->encode($data,-2, false); break;
			case 'GS1-128'     : $code = (new Encoders\Code128)->encode($data, 0, true); break;
			case 'GS1-128a'    : $code = (new Encoders\Code128)->encode($data, 1, true); break;
			case 'GS1-128b'    : $code = (new Encoders\Code128)->encode($data, 2, true); break;
			case 'GS1-128c'    : $code = (new Encoders\Code128)->encode($data, 3, true); break;
			case 'GS1-128ac'   : $code = (new Encoders\Code128)->encode($data,-1, true); break;
			case 'GS1-128bc'   : $code = (new Encoders\Code128)->encode($data,-2, true); break;
			case 'codabar'    : $code = (new Encoders\Codabar)->encode($data); break;
			case 'itf'        :
			case 'itf14'      :
				$code = (new Encoders\ITF)->encode($data);
				break;
			default: throw pException::InvalidInput("Unknown encode method - ".$symbology);
		}

		$this->parse_opts($opts);
		$this->render($code);
	}
}

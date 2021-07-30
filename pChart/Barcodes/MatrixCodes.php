<?php

namespace pChart\Barcodes;

use pChart\pColor;
use pChart\pException;

class MatrixCodes {

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
		$config['modules']['Shape']   = (isset($opts['modules']['Shape'])   ? strtolower($opts['modules']['Shape']) : '');
		$config['modules']['Density'] = (isset($opts['modules']['Density']) ? (float)$opts['modules']['Density'] : 1);

		$config["palette"] = [
			0 => new pColor(255), // CS - Color of spaces
			1 => new pColor(0), 	// CM - Color of modules
			2 => NULL, // C2 => new pColor(255,0, 0)
			3 => NULL, // C3 => new pColor(255,255, 0)
			4 => NULL, // C4 => new pColor(0,255, 0)
			5 => NULL, // C5 => new pColor(0,255, 255)
			6 => NULL, // C6 => new pColor(0,0, 255)
			7 => NULL, // C7 => new pColor(255,0, 255)
			8 => NULL, // C8 => new pColor(255)
			9 => NULL  // C9 => new pColor(0)
		];

		if (isset($opts['palette'])){
			$config["palette"] = array_replace($config["palette"], $opts['palette']);
		}

		# pre-allocate colors
		foreach($config['palette'] as $id => $color) {
			$config['palette'][$id] = $this->myPicture->allocatepColor($color);
		}

		// widths
		$config['widths'] = [
			'QuietArea' 	=> 1,
			'NarrowModules' => 1,
			'WideModules' 	=> 3,
			'NarrowSpace' 	=> 1,
			'w4' => 1,
			'w5' => 1,
			'w6' => 1,
			'w7' => 1,
			'w8' => 1,
			'w9' => 1
		];

		if (isset($opts['widths'])){
			$config['widths'] = array_replace($config['widths'], $opts['widths']);
		}

		// scale
		$config['scale'] = (isset($opts['scale']) ? (float)$opts['scale'] : 4);

		// dimentions
		$config['Width']  = (isset($opts['Width'])  ? (int)$opts['Width']  : NULL);
		$config['Height'] = (isset($opts['Height']) ? (int)$opts['Height'] : NULL);

		return $config;
	}

	public function render($config, $code)
	{
		# calculate_size
		$widths = array_values($config['widths']);
		$width  = (2 * $widths[0]) + ($code['width']  * $widths[1]);
		$height = (2 * $widths[0]) + ($code['height'] * $widths[1]);

		$x = $config['StartX'];
		$y = $config['StartY'];
		$w = (!is_null($config['Width']))  ? $config['Width']  : intval(ceil($width * $config['scale']));
		$h = (!is_null($config['Height'])) ? $config['Height'] : intval(ceil($height * $config['scale']));

		if ($width > 0 && $height > 0) {
			$scale = min($w / $width, $h / $height);
			$scale = (($scale > 1) ? floor($scale) : 1);
		} else {
			$scale = 1;
		}

		$wh = $widths[1] * $scale;

		$md = $config['modules']['Density'];
		$whd = intval(ceil($wh * $md));
		if ($config['modules']['Shape'] == 'r'){
			$md = 0;
		}

		$offset = (1 - $md) * $whd / 2;
		$image = $this->myPicture->gettheImage();

		foreach ($code['matrix'] as $by => $row) {

			$y1 = intval(floor($y + $by * $wh + $offset));
			
			foreach ($row as $bx => $color) {
				$mc = $config['palette'][$color];
				$x1 = intval(floor($x + $bx * $wh + $offset));
				$offwh = $whd - 1;

				switch ($config['modules']['Shape']) {
					case 'r':
						imagefilledellipse($image, $x1, $y1, $whd, $whd, $mc);
						break;
					case 'x':
						imageline($image, $x1, $y1, $x1 + $offwh, $y1 + $offwh, $mc);
						imageline($image, $x1, $y1 + $offwh, $x1 + $offwh, $y1, $mc);
						break;
					default:
						imagefilledrectangle($image, $x1, $y1, $x1 + $offwh, $y1 + $offwh, $mc);
				}
			}
		}
	}

	public function draw($data, string $symbology, array $opts = [])
	{
		switch ($symbology) {
			case 'dmtx':
			case 'dmtxs':
				$code = (new Encoders\DMTX())->dmtx_encode($data, false, false);
				break;
			case 'dmtxr': 
				$code = (new Encoders\DMTX())->dmtx_encode($data, true,  false);
				break;
			case 'dmtxgs1':
			case 'dmtxsgs1':
				$code = (new Encoders\DMTX())->dmtx_encode($data, false, true);
				break;
			case 'dmtxrgs1': 
				$code = (new Encoders\DMTX())->dmtx_encode($data, true,  true);
				break;
			default: throw pException::InvalidInput("Unknown encode method - ".$symbology);
		}

		$opts = $this->options + $this->parse_opts($opts);
		$this->render($opts, $code);
	}
}

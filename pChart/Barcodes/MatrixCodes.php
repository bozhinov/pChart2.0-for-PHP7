<?php

namespace pChart\Barcodes;

use pChart\pException;

class MatrixCodes extends pConf {

	private $myPicture;

	function __construct(\pChart\pDraw $pChartObject)
	{
		$this->myPicture = $pChartObject;
	}

	private function parse_opts($opts)
	{
		$defaults = [
			'scale' => 4,
			'width' => NULL,
			'height' => NULL,
			'modules' => [
				'Shape' => '',
				'Density' => 1
			],
			'widths' => [
				'QuietArea' 	=> 1,
				'NarrowModules' => 1,
				'WideModules' 	=> 3,
				'NarrowSpace' 	=> 1
			]#,
			#'palette' => [
			#	0 => new pColor(255), // CS - Color of spaces
			#	1 => new pColor(0) 	// CM - Color of modules
			#]
		];

		$this->apply_user_options($opts, $defaults);
	}

	public function render($code)
	{
		$opts = $this->options;

		# calculate_size
		$widths = array_values($opts['widths']);
		$width  = (2 * $widths[0]) + ($code['width']  * $widths[1]);
		$height = (2 * $widths[0]) + ($code['height'] * $widths[1]);

		$x = intval($opts['StartX']);
		$y = intval($opts['StartY']);
		$w = (!is_null($opts['width']))  ? intval($opts['width'])  : intval(ceil($width * $opts['scale']));
		$h = (!is_null($opts['height'])) ? intval($opts['height']) : intval(ceil($height * $opts['scale']));

		if ($width > 0 && $height > 0) {
			$scale = min($w / $width, $h / $height);
			$scale = ($scale > 1) ? $scale : 1;
		} else {
			$scale = 1;
		}

		$wh = $widths[1] * $scale;

		$md = (float)$opts['modules']['Density'];
		$whd = intval(ceil($wh * $md));
		if (strtolower($opts['modules']['Shape']) == 'r'){
			$md = 0;
		}

		$offset = (1 - $md) * $whd / 2;
		$image = $this->myPicture->gettheImage();
		$palette = array_values($opts['palette']);

		# pre-allocate colors
		foreach($palette as $id => $color) {
			if ($color instanceof \pChart\pColor){
				$palette[$id] = $this->myPicture->allocatepColor($color);
			}
		}

		foreach ($code['matrix'] as $by => $row) {

			$y1 = intval(floor($y + $by * $wh + $offset));
			
			foreach ($row as $bx => $color) {
				$mc = $palette[$color];
				$x1 = intval(floor($x + $bx * $wh + $offset));
				$offwh = $whd - 1;

				switch (strtolower($opts['modules']['Shape'])) {
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

		$this->parse_opts($opts);
		$this->render($code);
	}
}

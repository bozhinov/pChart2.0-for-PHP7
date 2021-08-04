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
		# calculate_size
		$widths = array_values($this->options['widths']);
		$width  = (2 * $widths[0]) + ($code['width']  * $widths[1]);
		$height = (2 * $widths[0]) + ($code['height'] * $widths[1]);

		$x = intval($this->options['StartX']);
		$y = intval($this->options['StartY']);
		$w = (!is_null($this->options['width']))  ? intval($this->options['width'])  : intval(ceil($width * $this->options['scale']));
		$h = (!is_null($this->options['height'])) ? intval($this->options['height']) : intval(ceil($height * $this->options['scale']));

		if ($width > 0 && $height > 0) {
			$scale = min($w / $width, $h / $height);
			$scale = ($scale > 1) ? $scale : 1;
		} else {
			$scale = 1;
		}

		$wh = $widths[1] * $scale;

		$shape = strtolower($this->options['modules']['Shape']);
		$md = ($shape == 'r') ? 0 : (float)$this->options['modules']['Density'];
		$whd = intval(ceil($wh * $md));

		$offset = (1 - $md) * $whd / 2;
		$image = $this->myPicture->gettheImage();
		$palette = array_values($this->options['palette']);

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

				switch ($shape) {
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

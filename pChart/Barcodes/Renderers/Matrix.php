<?php

namespace pChart\Barcodes\Renderers;

class Matrix {

	public function render($myPicture, $config, $code)
	{
		# calculate_size
		$widths = array_values($config['widths']);
		$width  = (2 * $widths[0]) + ($code['width']  * $widths[1]);
		$height = (2 * $widths[0]) + ($code['height'] * $widths[1]);

		$x = 0;
		$y = 0;
		$w = (!is_null($config['Width']))  ? $config['Width']  : intval(ceil($width * $config['scale']['Horizontal']));
		$h = (!is_null($config['Height'])) ? $config['Height'] : intval(ceil($height * $config['scale']['Vertial']));

		$image = $myPicture->gettheImage();

		if (!is_null($width) && !is_null($height)) {
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

		# Color pre-allocation speeds things up significantly
		$colors = [];
		foreach ($code['matrix'] as $by => $row) {
			foreach ($row as $bx => $color) {
				$colors[$color] = 1;
			}
		}

		foreach($colors as $c => $valid){
			$colors[$c] = $myPicture->allocatepColor($config['palette'][$c]);
		}

		$StartX = $config['StartX'];
		$StartY = $config['StartY'];

		foreach ($code['matrix'] as $by => $row) {

			$y1 = intval(floor($y + $by * $wh + $offset)) + $StartY;
			
			foreach ($row as $bx => $color) {
				$mc = $colors[$color];
				$x1 = intval(floor($x + $bx * $wh + $offset)) + $StartX;
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
}

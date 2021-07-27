<?php

namespace pChart\Barcodes\Renderers;

class Matrix extends Base {

	protected function calculate_size()
	{
		$width  = (2 * $this->widths[0]) + ($this->code['width']  * $this->widths[1]);
		$height = (2 * $this->widths[0]) + ($this->code['height'] * $this->widths[1]);

		return [$width, $height];
	}

	public function render_image($x, $y, $w, $h)
	{
		list($width, $height) = $this->calculate_size();
		$image = $this->myPicture->gettheImage();

		if ($width && $height) {
			$scale = min($w / $width, $h / $height);
			$scale = (($scale > 1) ? floor($scale) : 1);
			$x = floor($x + ($w - $width * $scale) / 2);
			$y = floor($y + ($h - $height * $scale) / 2);
		} else {
			$scale = 1;
			$x = floor($x + $w / 2);
			$y = floor($y + $h / 2);
		}

		$x += $this->widths[0] * $scale;
		$y += $this->widths[0] * $scale;
		$wh = $this->widths[1] * $scale;

		$md = $this->config['modules']['Density'];
		$whd = intval(ceil($wh * $md));
		if ($this->config['modules']['Shape'] == 'r'){
			$md = 0;
		}

		$offset = (1 - $md) * $whd / 2;

		# Color pre-allocation speeds things up significantly
		$colors = [];
		foreach ($this->code['matrix'] as $by => $row) {
			foreach ($row as $bx => $color) {
				$colors[$color] = 1;
			}
		}

		foreach($colors as $c => $valid){
			$colors[$c] = $this->myPicture->allocatepColor($this->config['palette'][$c]);
		}

		foreach ($this->code['matrix'] as $by => $row) {

			$y1 = intval(floor($y + $by * $wh + $offset));
			
			foreach ($row as $bx => $color) {
				$mc = $colors[$color];
				$x1 = intval(floor($x + $bx * $wh + $offset));
				$offwh = $whd - 1;

				switch ($this->config['modules']['Shape']) {
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

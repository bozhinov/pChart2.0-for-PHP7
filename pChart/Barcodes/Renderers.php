<?php

namespace pChart\Barcodes;

class Renderers {

	public static function matrix($image, $config, $code)
	{
		# calculate_size
		$widths = array_values($config['widths']);
		$width  = (2 * $widths[0]) + ($code['width']  * $widths[1]);
		$height = (2 * $widths[0]) + ($code['height'] * $widths[1]);

		$x = $config['StartX'];
		$y = $config['StartY'];
		$w = (!is_null($config['Width']))  ? $config['Width']  : intval(ceil($width * $config['scale']['Horizontal']));
		$h = (!is_null($config['Height'])) ? $config['Height'] : intval(ceil($height * $config['scale']['Vertial']));

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

	public static function linear($image, $config, $code)
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
		$w = (!is_null($config['Width']))  ? $config['Width']  : intval(ceil($width * $config['scale']['Horizontal']));
		$h = (!is_null($config['Height'])) ? $config['Height'] : intval(ceil(80 * $config['scale']['Vertial']));

		$lsize = $config['label']['Size'];

		if ($width > 0) {
			$scale = $w / $width;
			$scale = (($scale > 1) ? floor($scale) : 1);
		} else {
			$scale = 1;
		}

		foreach ($code as $block) {

			if (isset($block['l'])) {
				$ly = (isset($block['l'][1]) ? (float)$block['l'][1] : 1);
				$my = round($y + min($h, $h + ($ly - 1) * $config['label']['Height']));
				$ly = intval($ly);
				$my = intval($my);
			} else {
				$my = $y + $h;
			}

			$mx = $x;

			foreach ($block['m'] as $module) {
				$mw = $mx + $module[1] * $widths[$module[2]] * $scale;
				imagefilledrectangle($image, $mx, $y, intval($mw - 1), $my - 1, $config['palette'][$module[0]]);
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
}
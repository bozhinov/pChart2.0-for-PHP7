<?php

namespace pChart\Barcodes\Renderers;

class Linear {

	public function render($image, $config, $code)
	{
		# calculate_size
		$width = 0;
		$widths = array_values($config['widths']);
		foreach ($code as $block){
			foreach ($block['m'] as $module){
				$width += $module[1] * $widths[$module[2]];
			}
		}

		$x = 0;
		$y = 0;
		$w = (!is_null($config['Width']))  ? $config['Width']  : intval(ceil($width * $config['scale']['Horizontal']));
		$h = (!is_null($config['Height'])) ? $config['Height'] : intval(ceil(80 * $config['scale']['Vertial']));

		$lsize = $config['label']['Size'];

		if ($width > 0) {
			$scale = $w / $width;
			$scale = (($scale > 1) ? floor($scale) : 1);
		} else {
			$scale = 1;
		}

		$StartX = $config['StartX'];
		$StartY = $config['StartY'];

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
				imagefilledrectangle($image, $mx + $StartX, $y + $StartY, intval($mw - 1) + $StartX, $my - 1 + $StartY, $config['palette'][$module[0]]);
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
						imagettftext($image, $lsize, 0, $lx + $StartX, $ly + $StartY, $config['label']['Color'], realpath($config['label']['TTF']), $text);
					} else {
						imagestring($image,  $lsize, $lx + $StartX, $ly + $StartY, $text, $config['label']['Color']);
					}
				}
			}

			$x = $mx;
		}
	}

}

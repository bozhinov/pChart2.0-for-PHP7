<?php

namespace pChart\Barcodes\Renderers;

class Linear extends Base {

	public function calculate_size()
	{
		$width = 0;

		foreach ($this->code as $block){
			foreach ($block['m'] as $module){
				$width += $module[1] * $this->widths[$module[2]];
			}
		}

		return [$width, 80];
	}

	public function render_image($x, $y, $w, $h)
	{
		list($width, ) = $this->calculate_size();
		$lsize = $this->config['label']['Size'];
		$textColor = $this->myPicture->allocatepColor($this->config['label']['Color']);
		$image = $this->myPicture->gettheImage();

		if ($width > 0) {
			$scale = $w / $width;
			$scale = (($scale > 1) ? floor($scale) : 1);
			$x = floor($x + ($w - $width * $scale) / 2);
		} else {
			$scale = 1;
			$x = floor($x + $w / 2);
		}

		$x = intval($x);

		$palette = [];
		foreach($this->config['palette'] as $id => $color) {
			if ($color instanceof \pChart\pColor){
				$palette[$id] = $this->myPicture->allocatepColor($color);
			}
		}

		$StartX = $this->config['StartX'];
		$StartY = $this->config['StartY'];

		foreach ($this->code as $block) {

			if (isset($block['l'])) {
				$ly = (isset($block['l'][1]) ? (float)$block['l'][1] : 1);
				$my = round($y + min($h, $h + ($ly - 1) * $this->config['label']['Height']));
				$ly = intval($ly);
				$my = intval($my);
			} else {
				$my = $y + $h;
			}

			$mx = $x;

			foreach ($block['m'] as $module) {
				$mw = $mx + $module[1] * $this->widths[$module[2]] * $scale;
				imagefilledrectangle($image, $mx + $StartX, $y + $StartY, intval($mw - 1) + $StartX, $my - 1 + $StartY, $palette[$module[0]]);
				$mx = $mw;
			}

			if ($this->config['label']['Skip'] != TRUE) {
				if (isset($block['l'])) {
					$text = $block['l'][0];
					$lx = (isset($block['l'][2]) ? (float)$block['l'][2] : 0.5);
					$lx = ($x + ($mx - $x) * $lx);
					$lw = imagefontwidth($lsize) * strlen($text);
					$lx = intval(round($lx - $lw / 2));
					$ly = ($y + $h + $ly * $this->config['label']['Height']);
					$ly = intval(round($ly - imagefontheight($lsize)));
					if (!is_null($this->config['label']['TTF'])) {
						$ly +=($lsize*2) + $this->config['label']['Offset'];
						imagettftext($image, $lsize, 0, $lx + $StartX, $ly + $StartY, $textColor, realpath($this->config['label']['TTF']), $text);
					} else {
						imagestring($image,  $lsize, $lx + $StartX, $ly + $StartY, $text, $textColor);
					}
				}
			}

			$x = $mx;
		}
	}

}

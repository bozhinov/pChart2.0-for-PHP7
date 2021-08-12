<?php

namespace pChart\Barcodes\Linear;

class Postnet {

	public function encode($code, $opts) 
	{
		$orig = $code;
		// bar lenght
		if ($opts['mode'] == "planet") {
			$barlen = [
				0 => [1, 1, 2, 2, 2],
				1 => [2, 2, 2, 1, 1],
				2 => [2, 2, 1, 2, 1],
				3 => [2, 2, 1, 1, 2],
				4 => [2, 1, 2, 2, 1],
				5 => [2, 1, 2, 1, 2],
				6 => [2, 1, 1, 2, 2],
				7 => [1, 2, 2, 2, 1],
				8 => [1, 2, 2, 1, 2],
				9 => [1, 2, 1, 2, 2]
			];
		} else {
			$barlen = [
				0 => [2, 2, 1, 1, 1],
				1 => [1, 1, 1, 2, 2],
				2 => [1, 1, 2, 1, 2],
				3 => [1, 1, 2, 2, 1],
				4 => [1, 2, 1, 1, 2],
				5 => [1, 2, 1, 2, 1],
				6 => [1, 2, 2, 1, 1],
				7 => [2, 1, 1, 1, 2],
				8 => [2, 1, 1, 2, 1],
				9 => [2, 1, 2, 1, 1]
			];
		}

		$code = str_replace('-', '', $code);
		$code = str_replace(' ', '', $code);

		// calculate checksum
		$sum = array_sum(str_split($code));

		$chkd = ($sum % 10);
		if ($chkd > 0) {
			$chkd = (10 - $chkd);
		}
		$code .= $chkd;
		$len = strlen($code);
		// start bar
		$block = [
			[1, 1, 1, 2, 0],
			[0, 1, 1, 2, 0]
		];

		for ($i = 0; $i < $len; ++$i) {
			for ($j = 0; $j < 5; ++$j) {
				$h = $barlen[$code[$i]][$j];
				$p = floor(1 / $h);
				$block[] = [1, 1, 1, $h, $p];
				$block[] = [0, 1, 1, 2, 0];
			}
		}
		// end bar
		$block[] = [1, 1, 1, 2, 0];

		return [
			[
				'm' => $block,
				'l' => [$orig]
			]
		];
	}
}
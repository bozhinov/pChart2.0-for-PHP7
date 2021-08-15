<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class Code11 {

	public function encode(string $code, array $opts)
	{
		if (!preg_match('/^[\d]+$/', $code)){
			throw pException::InvalidInput("Text can not be encoded");
		}

		$chr = [
			'0' => '111121',
			'1' => '211121',
			'2' => '121121',
			'3' => '221111',
			'4' => '112121',
			'5' => '212111',
			'6' => '122111',
			'7' => '111221',
			'8' => '211211',
			'9' => '211111',
			'-' => '112111',
			'S' => '112211'
		];
		$len = strlen($code);
		// calculate check digit C
		$p = 1;
		$check = 0;
		for ($i = ($len - 1); $i >= 0; --$i) {
			$dval = ($code[$i] == '-') ? 10 : intval($code[$i]);
			$check += ($dval * $p);
			++$p;
			if ($p > 10) {
				$p = 1;
			}
		}
		$check %= 11;
		if ($check == 10) {
			$check = '-';
		}
		$orig = "  ".$code." ";
		$code .= $check;
		if ($len > 10) {
			// calculate check digit K
			$p = 1;
			$check = 0;
			for ($i = $len; $i >= 0; --$i) {
				$dval = ($code[$i] == '-') ? 10 : intval($code[$i]);
				$check += ($dval * $p);
				++$p;
				if ($p > 9) {
					$p = 1;
				}
			}
			$check %= 11;
			$code .= $check;
			$orig .= " ";
			++$len;
		}

		$code = 'S' . $code . 'S';
		$len += 3;
		$block = [];
		for ($i = 0; $i < $len; ++$i) {
			$seq = $chr[$code[$i]];
			for ($j = 0; $j < 6; ++$j) {
				$t = (($j % 2) == 0); // bar : space
				$block[] = [$t, $seq[$j], 1];
			}
		}

		return [
			[
				'm' => $block,
				'l' => [$orig]
			]
		];
	}
}
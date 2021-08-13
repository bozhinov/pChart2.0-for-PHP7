<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class s25 {

	private function checksum_s25($code)
	{
		$len = strlen($code);
		$sum = 0;
		for ($i = 0; $i < $len; $i+=2) {
			$sum += $code[$i];
		}
		$sum *= 3;
		for ($i = 1; $i < $len; $i+=2) {
			$sum += $code[$i];
		}
		$r = $sum % 10;
		if ($r > 0) {
			$r = (10 - $r);
		}
		return $r;
	}

	public function encode(string $code, array $opts)
	{
		$orig = $code;

		$chr = [
			'0' => '10101110111010',
			'1' => '11101010101110',
			'2' => '10111010101110',
			'3' => '11101110101010',
			'4' => '10101110101110',
			'5' => '11101011101010',
			'6' => '10111011101010',
			'7' => '10101011101110',
			'8' => '10101110111010',
			'9' => '10111010111010'
			];

		if (strtolower($opts['mode']) == 'checksum') {
			// add checksum
			$code .= $this->checksum_s25($code);
		}
		if ((strlen($code) % 2) != 0) {
			// add leading zero if code-length is odd
			$code = '0' . $code;
		}
		$seq = '11011010';
		$clen = strlen($code);
		for ($i = 0; $i < $clen; ++$i) {
			$digit = $code[$i];
			if (!isset($chr[$digit])) {
				throw pException::InvalidInput("Text can not be encoded by s25");
			}
			$seq .= $chr[$digit];
		}
		$seq .= '1101011';
		$len = strlen($seq);
		$w = 0;
		$block = [];
		for ($i = 0; $i < $len; ++$i) {
			$w += 1;
			if (($i == ($len - 1)) OR (($i < ($len - 1)) AND ($seq[$i] != $seq[$i + 1]))) {
				$t = ($seq[$i] == '1'); // bar : space
				$block[] = [$t, $w, 1];
				$w = 0;
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
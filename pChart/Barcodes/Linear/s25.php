<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class s25 {

	private function checksum_s25($code) 
	{
		$sum = 0;
		foreach(str_split($code) as $i => $chr)
		{
			$sum += ($i & 1) ? intval($chr) : intval($chr) * 3;
		}

		$r = $sum % 10;
		if ($r > 0) {
			$r = (10 - $r);
		}

		return $r;
	}

	public function encode(string $code, array $opts)
	{
		if (!preg_match('/^[\d]+$/', $code)){
			throw pException::InvalidInput("Text can not be encoded by s25");
		}

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
			$seq .= $chr[$code[$i]];
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
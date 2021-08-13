<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class i25 {

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
			'0' => '11221',
			'1' => '21112',
			'2' => '12112',
			'3' => '22111',
			'4' => '11212',
			'5' => '21211',
			'6' => '12211',
			'7' => '11122',
			'8' => '21121',
			'9' => '12121',
			'A' => '11',
			'Z' => '21'
		];
		if (strtolower($opts['mode']) == 'checksum') {
			// add checksum
			$code .= $this->checksum_s25($code);
		}
		if ((strlen($code) % 2) != 0) {
			// add leading zero if code-length is odd
			$code = '0' . $code;
		}
		// add start and stop codes
		$code = 'AA' . strtolower($code) . 'ZA';

		$len = strlen($code);
		$block = [];
		for ($i = 0; $i < $len; $i = ($i + 2)) {
			$char_bar = $code[$i];
			$char_space = $code[$i + 1];
			if ((!isset($chr[$char_bar])) OR (!isset($chr[$char_space]))) {
				throw pException::InvalidInput("Text can not be encoded by i25");
			}
			// create a bar-space sequence
			$seq = '';
			$chrlen = strlen($chr[$char_bar]);
			for ($s = 0; $s < $chrlen; $s++) {
				$seq .= $chr[$char_bar][$s] . $chr[$char_space][$s];
			}
			$seqlen = strlen($seq);
			for ($j = 0; $j < $seqlen; ++$j) {
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
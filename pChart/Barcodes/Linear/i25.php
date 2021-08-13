<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class i25 {

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
			throw pException::InvalidInput("Text can not be encoded by i25");
		}

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
			$code .= $this->checksum_s25($code);
		}
		if ((strlen($code) % 2) != 0) {
			$code = '0' . $code;
		}
		// add start and stop codes
		$code = 'AA' . strtolower($code) . 'ZA';

		$len = strlen($code);
		$block = [];

		for ($i = 0; $i < $len; $i += 2) {
			$char_bar = $code[$i];
			$char_space = $code[$i + 1];

			$chrlen = strlen($chr[$char_bar]);
			for ($s = 0; $s < $chrlen; $s++) {
				$block[] = [1, $chr[$char_bar][$s], 1];
				$block[] = [0, $chr[$char_space][$s], 1];
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
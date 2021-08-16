<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class b2of5 {

	private $s_chr = [
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

	private $i_chr = [
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

	public function encode(string $code, array $opts)
	{
		if (!preg_match('/^[\d]+$/', $code)){
			throw pException::InvalidInput("Text can not be encoded");
		}
		$orig = $code;

		if (substr($opts['mode'], -1) == '+') {
			$code .= $this->checksum_s25($code);
		}

		if ((strlen($code) % 2) != 0) {
			$code = '0' . $code;
		}

		switch(strtolower(substr($opts['mode'], 0 , 5))){
			case "stand": # standard
				$block = $this->encode_s25($code);
				break;
			case "inter": # interleaved
				$block = $this->encode_i25($code);
				break;
			default:
				$block = [];
		}

		return [
			[
				'm' => $block,
				'l' => [$orig]
			]
		];
	}

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

	private function encode_s25($code)
	{
		$seq = '11011010';
		$clen = strlen($code);
		for ($i = 0; $i < $clen; ++$i) {
			$seq .= $this->s_chr[$code[$i]];
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

		return $block;
	}

	private function encode_i25($code)
	{
		// add start and stop codes
		$code = 'AA' . $code . 'ZA';
		$block = [];

		foreach(str_split($code, 2) as $c){
			$chrlen = strlen($this->i_chr[$c[0]]);
			for ($s = 0; $s < $chrlen; $s++) {
				$block[] = [1, $this->i_chr[$c[0]][$s], 1];
				$block[] = [0, $this->i_chr[$c[1]][$s], 1];
			}
		}

		return $block;
	}

}
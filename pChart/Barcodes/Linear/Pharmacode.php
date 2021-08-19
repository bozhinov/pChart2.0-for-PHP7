<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class Pharmacode {

	public function encode(string $code, array $opts)
	{
		if (!preg_match('/^[\d]+$/', $code)){
			throw pException::InvalidInput("Text can not be encoded");
		}

		$orig = $code;

		if (strtoupper($opts['mode']) == "2T"){
			$block = $this->pharmacode2t(intval($code));
		} else {
			$block = $this->pharmacode(intval($code));
		}

		return [
			[
				'm' => $block,
				'l' => [$orig]
			]
		];
	}

	private function pharmacode($code)
	{
		$seq = '';
		while ($code > 0) {
			if (($code % 2) == 0) {
				$seq .= '11100';
				$code -= 2;
			} else {
				$seq .= '100';
				$code -= 1;
			}
			$code /= 2;
		}
		$seq = substr($seq, 0, -2);
		$seq = strrev($seq);
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

	private function pharmacode2t($code)
	{
		$block = [];
		do {
			$c = $code % 3;
			if ($c == 0){
				$rev = 3;
				$h = 2;
			} else {
				$rev = $c;
				$h = 1;
			}
			$code = ($code - $rev) / 3;
			$block[] = [0, 1, 1, 2, 0];
			$block[] = [1, 1, 1, $h, $c % 2];

		} while ($code != 0);

		return array_reverse($block);
	}
}

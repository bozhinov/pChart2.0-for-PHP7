<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class Pharmacode {

	public function encode(string $code, array $opts)
	{
		if (!preg_match('/^[\d]+$/', $code)){
			throw pException::InvalidInput("Text can not be encoded");
		}

		if (strtoupper($opts['mode']) == "2T"){
			return $this->pharmacode2t($code);
		} else {
			return $this->pharmacode($code);
		}
	}

	public function pharmacode($code)
	{
		$seq = '';
		$orig = $code;
		$code = intval($code);
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

		return [
			[
				'm' => $block,
				'l' => [$orig]
			]
		];
	}

	public function pharmacode2t($code)
	{
		$seq = '';
		$orig = $code;
		$code = intval($code);
		do {
			switch ($code % 3) {
				case 0: {
						$seq .= '3';
						$code = ($code - 3) / 3;
						break;
					}
				case 1: {
						$seq .= '1';
						$code = ($code - 1) / 3;
						break;
					}
				case 2: {
						$seq .= '2';
						$code = ($code - 2) / 3;
						break;
					}
			}
		} while ($code != 0);

		$seq = strrev($seq);
		$len = strlen($seq);
		$block = [];

		for ($i = 0; $i < $len; ++$i) {
			$p = 0;
			$h = 1;
			switch ($seq[$i]) {
				case '1':
					$p = 1;
					break;
				case '3':
					$h = 2;
					break;
			}
			$block[] = [1, 1, 1, $h, $p];
			$block[] = [0, 1, 1, 2, 0];
		}

		return [
			[
				'm' => $block,
				'l' => [$orig]
			]
		];
	}
}

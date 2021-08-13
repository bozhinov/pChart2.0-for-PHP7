<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class MSI {

	public function encode(string $code, array $opts)
	{
		$orig = $code;
		$code = strtoupper($code);
		if (!preg_match('/^[0-9a-fA-F]+$/', $code)){
			throw pException::InvalidInput("Text can not be encoded");
		}

		$chr = [
				'0' => '100100100100',
				'1' => '100100100110',
				'2' => '100100110100',
				'3' => '100100110110',
				'4' => '100110100100',
				'5' => '100110100110',
				'6' => '100110110100',
				'7' => '100110110110',
				'8' => '110100100100',
				'9' => '110100100110',
				'A' => '110100110100',
				'B' => '110100110110',
				'C' => '110110100100',
				'D' => '110110100110',
				'E' => '110110110100',
				'F' => '110110110110'
			];

		if ($opts['mode'] == "+") {
			// add checksum
			$clen = strlen($code);
			$p = 2;
			$check = 0;
			for ($i = ($clen - 1); $i >= 0; --$i) {
				$check += (hexdec($code[$i]) * $p);
				++$p;
				if ($p > 7) {
					$p = 2;
				}
			}
			$check %= 11;
			if ($check > 0) {
				$check = 11 - $check;
			}
			$code .= $check;
		}
		$seq = '110'; // left guard
		$clen = strlen($code);
		for ($i = 0; $i < $clen; ++$i) {
			$seq .= $chr[$code[$i]];
		}
		$seq .= '1001'; // right guard

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
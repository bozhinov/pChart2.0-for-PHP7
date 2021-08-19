<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class Eanext {

	public function encode(string $code, array $opts)
	{
		if (!preg_match('/^[\d]+$/', $code)){
			throw pException::InvalidInput("Text can not be encoded by Eanext");
		}

		$orig = $code;
		$len = (strtoupper($opts['mode']) == "EAN5") ? 5 : 2;
		$code = str_pad($code, $len, '0', STR_PAD_LEFT); //Padding
		$code_array = array_map(fn(string $d): int => (int) $d, str_split($code));

		// calculate check digit
		if ($len == 2) {
			$chkd = intval($code) % 4;
		} elseif ($len == 5) {
			$chkd = (3 * ($code_array[0] + $code_array[2] + $code_array[4])) + (9 * ($code_array[1] + $code_array[3]));
			$chkd %= 10;
		}

		//Convert digits to bars
		$codes = [
			'A' => [// left odd parity
				'0001101',
				'0011001',
				'0010011',
				'0111101',
				'0100011',
				'0110001',
				'0101111',
				'0111011',
				'0110111',
				'0001011'
				],
			'B' => [// left even parity
				'0100111',
				'0110011',
				'0011011',
				'0100001',
				'0011101',
				'0111001',
				'0000101',
				'0010001',
				'0001001',
				'0010111'
				]
		];

		$parities = [
			2 => [
				['A', 'A'],
				['A', 'B'],
				['B', 'A'],
				['B', 'B']
			],
			5 => [
				['B', 'B', 'A', 'A', 'A'],
				['B', 'A', 'B', 'A', 'A'],
				['B', 'A', 'A', 'B', 'A'],
				['B', 'A', 'A', 'A', 'B'],
				['A', 'B', 'B', 'A', 'A'],
				['A', 'A', 'B', 'B', 'A'],
				['A', 'A', 'A', 'B', 'B'],
				['A', 'B', 'A', 'B', 'A'],
				['A', 'B', 'A', 'A', 'B'],
				['A', 'A', 'B', 'A', 'B']
			]
		];

		$p = $parities[$len][$chkd];
		$seq = '1011'; // left guard bar
		$seq .= $codes[$p[0]][$code_array[0]];
		for ($i = 1; $i < $len; ++$i) {
			$seq .= '01'; // separator
			$seq .= $codes[$p[$i]][$code_array[$i]];
		}

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
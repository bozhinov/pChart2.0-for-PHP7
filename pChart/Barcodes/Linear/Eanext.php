<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class Eanext {

	public function encode(string $code, array $opts)
	{
		//Padding
		$orig = $code;
		$len = (strtoupper($opts['mode']) == "EAN5") ? 5 : 2;
		$code = str_pad($code, $len, '0', STR_PAD_LEFT);

		if (!preg_match('/^[\d]+$/', $code)){
			throw pException::InvalidInput("Text can not be encoded by Eanext");
		}

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
				0 => '0001101',
				1 => '0011001',
				2 => '0010011',
				3 => '0111101',
				4 => '0100011',
				5 => '0110001',
				6 => '0101111',
				7 => '0111011',
				8 => '0110111',
				9 => '0001011'
				],
			'B' => [// left even parity
				0 => '0100111',
				1 => '0110011',
				2 => '0011011',
				3 => '0100001',
				4 => '0011101',
				5 => '0111001',
				6 => '0000101',
				7 => '0010001',
				8 => '0001001',
				9 => '0010111'
				]
		];

		$parities = [
			2 => [
				0 => ['A', 'A'],
				1 => ['A', 'B'],
				2 => ['B', 'A'],
				3 => ['B', 'B']
			],
			5 => [
				0 => ['B', 'B', 'A', 'A', 'A'],
				1 => ['B', 'A', 'B', 'A', 'A'],
				2 => ['B', 'A', 'A', 'B', 'A'],
				3 => ['B', 'A', 'A', 'A', 'B'],
				4 => ['A', 'B', 'B', 'A', 'A'],
				5 => ['A', 'A', 'B', 'B', 'A'],
				6 => ['A', 'A', 'A', 'B', 'B'],
				7 => ['A', 'B', 'A', 'B', 'A'],
				8 => ['A', 'B', 'A', 'A', 'B'],
				9 => ['A', 'A', 'B', 'A', 'B']
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
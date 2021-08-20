<?php

namespace pChart\Barcodes\Linear;

class CodaBar {

	private $codabar_alphabet = [
			'0' => [1, 1, 1, 1, 1, 2, 2],
			'1' => [1, 1, 1, 1, 2, 2, 1],
			'4' => [1, 1, 2, 1, 1, 2, 1],
			'5' => [2, 1, 1, 1, 1, 2, 1],
			'2' => [1, 1, 1, 2, 1, 1, 2],
			'-' => [1, 1, 1, 2, 2, 1, 1],
			'$' => [1, 1, 2, 2, 1, 1, 1],
			'9' => [2, 1, 1, 2, 1, 1, 1],
			'6' => [1, 2, 1, 1, 1, 1, 2],
			'7' => [1, 2, 1, 1, 2, 1, 1],
			'8' => [1, 2, 2, 1, 1, 1, 1],
			'3' => [2, 2, 1, 1, 1, 1, 1],
			'C' => [1, 1, 1, 2, 1, 2, 2],
			'D' => [1, 1, 1, 2, 2, 2, 1],
			'A' => [1, 1, 2, 2, 1, 2, 1],
			'B' => [1, 2, 1, 2, 1, 1, 2],
			'*' => [1, 1, 1, 2, 1, 2, 2],
			'E' => [1, 1, 1, 2, 2, 2, 1],
			'T' => [1, 1, 2, 2, 1, 2, 1],
			'N' => [1, 2, 1, 2, 1, 1, 2],
			'.' => [2, 1, 2, 1, 2, 1, 1],
			'/' => [2, 1, 2, 1, 1, 1, 2],
			':' => [2, 1, 1, 1, 2, 1, 2],
			'+' => [1, 1, 2, 1, 2, 1, 2]
		];

	public function encode($data, $opts)
	{
		$data = strtoupper(preg_replace('/[^0-9ABCDENTabcdent*.\/:+$-]/', '', $data));
		$data = str_split($data);
		$blocks = [];

		foreach($data as $char){
			if (!empty($blocks)) {
				$blocks[] = [
					'm' => [[0, 3, 1]]
				];
			}
			$block = $this->codabar_alphabet[$char];
			$blocks[] = [
				'm' =>[
					[1, $block[0], 1],
					[0, $block[1], 1],
					[1, $block[2], 1],
					[0, $block[3], 1],
					[1, $block[4], 1],
					[0, $block[5], 1],
					[1, $block[6], 1]
				],
				'l' => [$char]
			];
		}
		return $blocks;
	}
}

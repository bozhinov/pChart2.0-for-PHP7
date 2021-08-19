<?php

namespace pChart\Barcodes\Linear;

class ITF {

	public function encode($data, $opts)
	{
		$data = preg_replace('/[^0-9]/', '', $data);
		if (strlen($data) % 2) {
			$data = '0' . $data;
		}

		$itf_alphabet = [
			'0' => [1, 1, 2, 2, 1],
			'1' => [2, 1, 1, 1, 2],
			'2' => [1, 2, 1, 1, 2],
			'3' => [2, 2, 1, 1, 1],
			'4' => [1, 1, 2, 1, 2],
			'5' => [2, 1, 2, 1, 1],
			'6' => [1, 2, 2, 1, 1],
			'7' => [1, 1, 1, 2, 2],
			'8' => [2, 1, 1, 2, 1],
			'9' => [1, 2, 1, 2, 1]
		];

		/* Quiet zone, start. */
		$blocks = [
			['m' => [
				[0, 10, 0],
				[1, 1, 1],
				[0, 1, 1],
				[1, 1, 1],
				[0, 1, 1]
			]]
		];

		/* Data. */
		foreach(str_split($data, 2) as $a) {
			$b1 = $itf_alphabet[$a[0]];
			$b2 = $itf_alphabet[$a[1]];
			$m = [];
			for($i = 0; $i < 5; $i++) {
				$m[] = [1, 1, $b1[$i]];
				$m[] = [0, 1, $b2[$i]];
			}
			$blocks[] = [
				'm' => $m,
				'l' => [$a]
			];
		}

		/* End, quiet zone. */
		$blocks[] = [
			'm' => [
				[1, 1, 2],
				[0, 1, 1],
				[1, 1, 1],
				[0, 10, 0]
			]
		];

		/* Return code. */
		return $blocks;
	}
}

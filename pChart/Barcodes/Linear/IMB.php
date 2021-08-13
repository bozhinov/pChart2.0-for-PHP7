<?php

namespace pChart\Barcodes\Linear;

use pChart\pException;

class IMB {

	public function encode(string $code, array $opts)
	{
		$orig = $code;

		$asc_chr = [
			4, 0, 2, 6, 3, 5, 1, 9, 8, 7, 1, 2, 0, 6, 4, 8, 2, 9, 5, 3, 0, 1, 3, 7, 4, 6, 8, 9, 2, 0, 5, 1, 9, 4,
			3, 8, 6, 7, 1, 2, 4, 3, 9, 5, 7, 8, 3, 0, 2, 1, 4, 0, 9, 1, 7, 0, 2, 4, 6, 3, 7, 1, 9, 5, 8
		];

		$dsc_chr = [
			7, 1, 9, 5, 8, 0, 2, 4, 6, 3, 5, 8, 9, 7, 3, 0, 6, 1, 7, 4, 6, 8, 9, 2, 5, 1, 7, 5, 4, 3, 8, 7, 6, 0, 2,
			5, 4, 9, 3, 0, 1, 6, 8, 2, 0, 4, 5, 9, 6, 7, 5, 2, 6, 3, 8, 5, 1, 9, 8, 7, 4, 0, 2, 6, 3
		];

		$asc_pos = [
			3, 0, 8, 11, 1, 12, 8, 11, 10, 6, 4, 12, 2, 7, 9, 6, 7, 9, 2, 8, 4, 0, 12, 7, 10, 9, 0, 7, 10, 5, 7, 9, 6,
			8, 2, 12, 1, 4, 2, 0, 1, 5, 4, 6, 12, 1, 0, 9, 4, 7, 5, 10, 2, 6, 9, 11, 2, 12, 6, 7, 5, 11, 0, 3, 2
		];

		$dsc_pos = [
			2, 10, 12, 5, 9, 1, 5, 4, 3, 9, 11, 5, 10, 1, 6, 3, 4, 1, 10, 0, 2, 11, 8, 6, 1, 12, 3, 8, 6, 4, 4, 11, 0,
			6, 1, 9, 11, 5, 3, 7, 3, 10, 7, 11, 8, 2, 10, 3, 5, 8, 0, 3, 12, 11, 8, 4, 5, 1, 3, 0, 7, 12, 9, 8, 10
		];

		$code_arr = explode('-', $code);
		$tracking_number = $code_arr[0];

		if (isset($code_arr[1])) {
			$routing_code = $code_arr[1];
		} else {
			$routing_code = '';
		}

		// Conversion of Routing Code
		switch (strlen($routing_code)) {
			case 0:
				$bin = 0;
				break;
			case 5:
				$bin = bcadd($routing_code, '1');
				break;
			case 9:
				$bin = bcadd($routing_code, '100001');
				break;
			case 11:
				$bin = bcadd($routing_code, '1000100001');
				break;
			default:
				throw pException::InvalidInput("Text can not be encoded by IMB");
				break;
		}

		$bin = bcmul($bin, 10);
		$bin = bcadd($bin, $tracking_number[0]);
		$bin = bcmul($bin, 5);
		$bin = bcadd($bin, $tracking_number[1]);
		$bin .= substr($tracking_number, 2, 18);
		// calculate frame check sequence
		$fcs = $this->imb_crc11fcs($bin);
		// convert binary data to codewords
		$codewords = [];
		$codewords[0] = bcmod($bin, 636) * 2;
		$bin = bcdiv($bin, 636);
		for ($i = 1; $i < 9; ++$i) {
			$codewords[$i] = bcmod($bin, 1365);
			$bin = bcdiv($bin, 1365);
		}
		$codewords[9] = $bin;
		if (($fcs >> 10) == 1) {
			$codewords[9] += 659;
		}
		// generate lookup tables
		$table2of13 = $this->imb_tables(2, 78);
		$table5of13 = $this->imb_tables(5, 1287);
		// convert codewords to characters
		$characters = [];
		$bitmask = 512;
		foreach ($codewords as $k => $val) {
			if ($val <= 1286) {
				$chrcode = $table5of13[$val];
			} else {
				$chrcode = $table2of13[($val - 1287)];
			}
			if (($fcs & $bitmask) > 0) {
				// bitwise invert
				$chrcode = ((~$chrcode) & 8191);
			}
			$characters[] = $chrcode;
			$bitmask /= 2;
		}
		$characters = array_reverse($characters);
		// build bars
		$block = [];
		for ($i = 0; $i < 65; ++$i) {
			$asc = (($characters[$asc_chr[$i]] & pow(2, $asc_pos[$i])) > 0);
			$dsc = (($characters[$dsc_chr[$i]] & pow(2, $dsc_pos[$i])) > 0);
			if ($asc AND $dsc) {
				// full bar (F)
				$p = 0;
				$h = 3;
			} elseif ($asc) {
				// ascender (A)
				$p = 0;
				$h = 2;
			} elseif ($dsc) {
				// descender (D)
				$p = 1;
				$h = 2;
			} else {
				// tracker (T)
				$p = 1;
				$h = 1;
			}
			$block[] = [1, 1, 1, $h, $p];
			$block[] = [0, 1, 1, 2, 0];
		}

		unset($block[129]);

		return [
			[
				'm' => $block,
				'l' => [$orig]
			]
		];
	}

	private function imb_crc11fcs($binary_code)
	{
		// convert to hexadecimal
		$binary_code = dechex(intval($binary_code));
		// pad to get 13 bytes;
		$binary_code = str_pad($binary_code, 26, '0', STR_PAD_LEFT);
		// convert string to array of bytes
		$code_arr = str_split($binary_code, 2);
		$genpoly = 0x0F35; // generator polynomial
		$fcs = 0x07FF; // Frame Check Sequence
		// do most significant byte skipping the 2 most significant bits
		$data = hexdec($code_arr[0]) << 5;
		for ($bit = 2; $bit < 8; ++$bit) {
			if (($fcs ^ $data) & 0x400) {
				$fcs = ($fcs << 1) ^ $genpoly;
			} else {
				$fcs = ($fcs << 1);
			}
			$fcs &= 0x7FF;
			$data <<= 1;
		}
		// do rest of bytes
		for ($byte = 1; $byte < 13; ++$byte) {
			$data = hexdec($code_arr[$byte]) << 3;
			for ($bit = 0; $bit < 8; ++$bit) {
				if (($fcs ^ $data) & 0x400) {
					$fcs = ($fcs << 1) ^ $genpoly;
				} else {
					$fcs = ($fcs << 1);
				}
				$fcs &= 0x7FF;
				$data <<= 1;
			}
		}
		return $fcs;
	}

	private function imb_reverse_us($num) 
	{
		$rev = 0;
		for ($i = 0; $i < 16; ++$i) {
			$rev <<= 1;
			$rev |= ($num & 1);
			$num >>= 1;
		}

		return $rev;
	}

	private function imb_tables($n, $size) 
	{
		$table = [];
		$lli = 0; // LUT lower index
		$lui = $size - 1; // LUT upper index
		for ($count = 0; $count < 8192; ++$count) {
			$bit_count = 0;
			for ($bit_index = 0; $bit_index < 13; ++$bit_index) {
				$bit_count += intval(($count & (1 << $bit_index)) != 0);
			}
			// if we don't have the right number of bits on, go on to the next value
			if ($bit_count == $n) {
				$reverse = ($this->imb_reverse_us($count) >> 3);
				// if the reverse is less than count, we have already visited this pair before
				if ($reverse >= $count) {
					// If count is symmetric, place it at the first free slot from the end of the list.
					// Otherwise, place it at the first free slot from the beginning of the list AND place $reverse ath the next free slot from the beginning of the list
					if ($reverse == $count) {
						$table[$lui] = $count;
						--$lui;
					} else {
						$table[$lli] = $count;
						++$lli;
						$table[$lli] = $reverse;
						++$lli;
					}
				}
			}
		}
		return $table;
	}
}
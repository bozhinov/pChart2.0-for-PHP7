<?php

namespace pChart\Barcodes\Linear;

class MSI {

    public function binseq_to_array($seq, $bararray) {
        $len = strlen($seq);
        $w = 0;
        $k = 0;
        for ($i = 0; $i < $len; ++$i) {
            $w += 1;
            if (($i == ($len - 1)) OR (($i < ($len - 1)) AND ($seq[$i] != $seq[$i + 1]))) {
                if ($seq[$i] == '1') {
                    $t = true; // bar
                } else {
                    $t = false; // space
                }
                $bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
                $bararray['maxw'] += $w;
                ++$k;
                $w = 0;
            }
        }
        return $bararray;
    }

    public function encode($code, $checksum = false) {
        $chr['0'] = '100100100100';
        $chr['1'] = '100100100110';
        $chr['2'] = '100100110100';
        $chr['3'] = '100100110110';
        $chr['4'] = '100110100100';
        $chr['5'] = '100110100110';
        $chr['6'] = '100110110100';
        $chr['7'] = '100110110110';
        $chr['8'] = '110100100100';
        $chr['9'] = '110100100110';
        $chr['A'] = '110100110100';
        $chr['B'] = '110100110110';
        $chr['C'] = '110110100100';
        $chr['D'] = '110110100110';
        $chr['E'] = '110110110100';
        $chr['F'] = '110110110110';
        if ($checksum) {
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
            $digit = $code[$i];
            if (!isset($chr[$digit])) {
                // invalid character
                return false;
            }
            $seq .= $chr[$digit];
        }
        $seq .= '1001'; // right guard
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
        return $this->binseq_to_array($seq, $bararray);
    }
}
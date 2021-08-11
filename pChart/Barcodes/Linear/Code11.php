<?php

namespace pChart\Barcodes\Linear;

class Code11 {

    public function encode($code) {
        $chr = array(
            '0' => '111121',
            '1' => '211121',
            '2' => '121121',
            '3' => '221111',
            '4' => '112121',
            '5' => '212111',
            '6' => '122111',
            '7' => '111221',
            '8' => '211211',
            '9' => '211111',
            '-' => '112111',
            'S' => '112211'
        );
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
        $k = 0;
        $w = 0;
        $seq = '';
        $len = strlen($code);
        // calculate check digit C
        $p = 1;
        $check = 0;
        for ($i = ($len - 1); $i >= 0; --$i) {
            $digit = $code[$i];
            if ($digit == '-') {
                $dval = 10;
            } else {
                $dval = intval($digit);
            }
            $check += ($dval * $p);
            ++$p;
            if ($p > 10) {
                $p = 1;
            }
        }
        $check %= 11;
        if ($check == 10) {
            $check = '-';
        }
        $code .= $check;
        if ($len > 10) {
            // calculate check digit K
            $p = 1;
            $check = 0;
            for ($i = $len; $i >= 0; --$i) {
                $digit = $code[$i];
                if ($digit == '-') {
                    $dval = 10;
                } else {
                    $dval = intval($digit);
                }
                $check += ($dval * $p);
                ++$p;
                if ($p > 9) {
                    $p = 1;
                }
            }
            $check %= 11;
            $code .= $check;
            ++$len;
        }
        $code = 'S' . $code . 'S';
        $len += 3;
        for ($i = 0; $i < $len; ++$i) {
            if (!isset($chr[$code[$i]])) {
                return false;
            }
            $seq = $chr[$code[$i]];
            for ($j = 0; $j < 6; ++$j) {
                if (($j % 2) == 0) {
                    $t = true; // bar
                } else {
                    $t = false; // space
                }
                $w = $seq[$j];
                $bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
                $bararray['maxw'] += $w;
                ++$k;
            }
        }
        return $bararray;
    }
}
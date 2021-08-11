<?php

namespace pChart\Barcodes\Linear;

class Postnet {

    public function encode($code, $planet = false) {
        // bar lenght
        if ($planet) {
            $barlen = Array(
                0 => Array(1, 1, 2, 2, 2),
                1 => Array(2, 2, 2, 1, 1),
                2 => Array(2, 2, 1, 2, 1),
                3 => Array(2, 2, 1, 1, 2),
                4 => Array(2, 1, 2, 2, 1),
                5 => Array(2, 1, 2, 1, 2),
                6 => Array(2, 1, 1, 2, 2),
                7 => Array(1, 2, 2, 2, 1),
                8 => Array(1, 2, 2, 1, 2),
                9 => Array(1, 2, 1, 2, 2)
            );
        } else {
            $barlen = Array(
                0 => Array(2, 2, 1, 1, 1),
                1 => Array(1, 1, 1, 2, 2),
                2 => Array(1, 1, 2, 1, 2),
                3 => Array(1, 1, 2, 2, 1),
                4 => Array(1, 2, 1, 1, 2),
                5 => Array(1, 2, 1, 2, 1),
                6 => Array(1, 2, 2, 1, 1),
                7 => Array(2, 1, 1, 1, 2),
                8 => Array(2, 1, 1, 2, 1),
                9 => Array(2, 1, 2, 1, 1)
            );
        }
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 2, 'bcode' => array());
        $k = 0;
        $code = str_replace('-', '', $code);
        $code = str_replace(' ', '', $code);
        $len = strlen($code);
        // calculate checksum
        $sum = 0;
        for ($i = 0; $i < $len; ++$i) {
            $sum += intval($code[$i]);
        }
        $chkd = ($sum % 10);
        if ($chkd > 0) {
            $chkd = (10 - $chkd);
        }
        $code .= $chkd;
        $len = strlen($code);
        // start bar
        $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
        $bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
        $bararray['maxw'] += 2;
        for ($i = 0; $i < $len; ++$i) {
            for ($j = 0; $j < 5; ++$j) {
                $h = $barlen[$code[$i]][$j];
                $p = floor(1 / $h);
                $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
                $bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
                $bararray['maxw'] += 2;
            }
        }
        // end bar
        $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
        $bararray['maxw'] += 1;
        return $bararray;
    }
}
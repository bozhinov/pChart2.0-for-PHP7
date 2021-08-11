<?php

namespace pChart\Barcodes\Linear;

class Pharmacode {

	public function encode($code, $type) {
		if ($type == "2T"){
			return $this->pharmacode2t($code);
		} else {
			return $this->pharmacode($code);
		}
	}

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

    public function pharmacode($code) {
        $seq = '';
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
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
        return $this->binseq_to_array($seq, $bararray);
    }

    public function pharmacode2t($code) {
        $seq = '';
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
        $k = 0;
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 2, 'bcode' => array());
        $len = strlen($seq);
        for ($i = 0; $i < $len; ++$i) {
            switch ($seq[$i]) {
                case '1': {
                        $p = 1;
                        $h = 1;
                        break;
                    }
                case '2': {
                        $p = 0;
                        $h = 1;
                        break;
                    }
                case '3': {
                        $p = 0;
                        $h = 2;
                        break;
                    }
            }
            $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
            $bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
            $bararray['maxw'] += 2;
        }
        unset($bararray['bcode'][($k - 1)]);
        --$bararray['maxw'];
        return $bararray;
    }


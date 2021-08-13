<?php

namespace pChart\Barcodes\Linear;

class RemainingBarcodes {

    protected $barcode_array;

    public function getBarcode($code, $type, $w = 2, $h = 30) {

        $this->setBarcode($code, $type);

        $width = ($this->barcode_array['maxw'] * $w);

		$png = imagecreate($width, $h);
		$bgcol = imagecolorallocate($png, 255, 255, 255);
		imagecolortransparent($png, $bgcol);
		$fgcol = imagecolorallocate($png, 0,0,0);

        // print bars
        $x = 0;
		#var_dump($this->barcode_array);
        foreach ($this->barcode_array['bcode'] as $k => $v) {
            $bw = $v['w'] * $w;
            $bh = $v['h'] * $h / $this->barcode_array['maxh'];
            if ($v['t']) {
                $y = intval($v['p'] * $h / $this->barcode_array['maxh']);
                imagefilledrectangle($png, $x, $y, intval($x + $bw), intval($y + $bh), $fgcol);
            }
            $x += $bw;
        }

		imagepng($png, "test.png");
    }

    protected function setBarcode($code, $type) {
        switch (strtoupper($type)) {
            case 'IMB': { // IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200
                    $arrcode = (new IMB())->encode($code);
                    break;
                }
            default: {
					die("WTF");
                }
        }
        $this->barcode_array = $arrcode;
    }
}

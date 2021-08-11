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
		var_dump($this->barcode_array);
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
            case 'S25': { // Standard 2 of 5
                    $arrcode = (new s25())->encode($code, false);
                    break;
                }
            case 'S25+': { // Standard 2 of 5 + CHECKSUM
                    $arrcode = (new s25())->encode($code, true);
                    break;
                }
            case 'I25': { // Interleaved 2 of 5
                    $arrcode = (new i25())->encode($code, false);
                    break;
                }
            case 'I25+': { // Interleaved 2 of 5 + CHECKSUM
                    $arrcode = (new i25())->encode($code, true);
                    break;
                }
            case 'EAN2': { // 2-Digits UPC-Based Extention
                    $arrcode = (new Eanext())->encode($code, 2);
                    break;
                }
            case 'EAN5': { // 5-Digits UPC-Based Extention
                    $arrcode = (new Eanext())->encode($code, 5);
                    break;
                }
            case 'MSI': { // MSI (Variation of Plessey code)
                    $arrcode = (new MSI())->encode($code, false);
                    break;
                }
            case 'MSI+': { // MSI + CHECKSUM (modulo 11)
                    $arrcode = (new MSI())->encode($code, true);
                    break;
                }
            case 'POSTNET': { // POSTNET
                    $arrcode = (new Postnet())->encode($code, false);
                    break;
                }
            case 'PLANET': { // PLANET
                    $arrcode = (new Postnet())->encode($code, true);
                    break;
                }
            case 'RMS4CC': { // RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)
                    $arrcode = (new Rms4cc())->encode($code, false);
                    break;
                }
            case 'KIX': { // KIX (Klant index - Customer index)
                    $arrcode = (new Rms4cc())->encode($code, true);
                    break;
                }
            case 'IMB': { // IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200
                    $arrcode = (new IMB())->encode($code);
                    break;
                }
            case 'CODE11': { // CODE 11
                    $arrcode = (new Code11())->encode($code);
                    break;
                }
            case 'PHARMA': { // PHARMACODE
                    $arrcode = (new Pharmacode())->encode($code);
                    break;
                }
            case 'PHARMA2T': { // PHARMACODE TWO-TRACKS
                    $arrcode = (new Pharmacode())->encode($code, "2T");
                    break;
                }
            default: {
					die("WTF");
                }
        }
        $this->barcode_array = $arrcode;
    }
}

<?php

namespace pChart\Barcodes;

use pChart\pColor;
use pChart\pException;
use pChart\Barcodes\Renderers\Linear;
use pChart\Barcodes\Renderers\Matrix;

class Barcodes {

	private $myPicture;
	private $options = ['StartX' => 0, 'StartY' => 0];

	function __construct(\pChart\pDraw $pChartObject)
	{
		$this->myPicture = $pChartObject;
	}

	public function set_start_position(int $x, int $y)
	{
		$this->options['StartX'] = $x;
		$this->options['StartY'] = $y;
	}

	private function parse_opts($opts, $isDataMatrix)
	{
		$config = [];

		if ($isDataMatrix){
			$config['scale']['Factor'] = 4;
		} else {
			$config['scale']['Factor'] = 1;
		}

		// label
		$config["label"] = ['Height' => 10, 'Size' => 1, 'Color' => new pColor(0), 'Skip' => FALSE, 'TTF' => NULL, 'Offset' => 0];

		if (isset($opts['label'])){
			$config["label"] = array_replace($config["label"], $opts['label']);
		}

		// palette
		$config["palette"] = [
			0 => new pColor(255), // CS - Color of spaces
			1 => new pColor(0), 	// CM - Color of modules
			2 => NULL, // C2 => new pColor(255,0, 0)
			3 => NULL, // C3 => new pColor(255,255, 0)
			4 => NULL, // C4 => new pColor(0,255, 0)
			5 => NULL, // C5 => new pColor(0,255, 255)
			6 => NULL, // C6 => new pColor(0,0, 255)
			7 => NULL, // C7 => new pColor(255,0, 255)
			8 => NULL, // C8 => new pColor(255)
			9 => NULL  // C9 => new pColor(0)
		];

		if (isset($opts['palette'])){
			$config["palette"] = array_replace($config["palette"], $opts['palette']);
		}

		// widths
		$config['widths'] = [
			'QuietArea' 	=> 1,
			'NarrowModules' => 1,
			'WideModules' 	=> 3,
			'NarrowSpace' 	=> 1,
			'w4' => 1,
			'w5' => 1,
			'w6' => 1,
			'w7' => 1,
			'w8' => 1,
			'w9' => 1
		];

		if (isset($opts['widths'])){
			$config['widths'] = array_replace($config['widths'], $opts['widths']);
		}

		// scale
		if (isset($opts['scale']['Factor'])) {
			$config['scale']['Factor'] = (float)$opts['scale']['Factor'];
		}
		$config['scale']['Horizontal'] = (isset($opts['scale']['Horizontal']) ? (float)$opts['scale']['Horizontal'] : $config["scale"]['Factor']);
		$config['scale']['Vertial']	 = (isset($opts['scale']['Vertial']) 	? (float)$opts['scale']['Vertial'] 	  : $config["scale"]['Factor']);

		// matrix modules
		$config['modules']['Shape']   = (isset($opts['modules']['Shape'])   ? strtolower($opts['modules']['Shape']) : '');
		$config['modules']['Density'] = (isset($opts['modules']['Density']) ? (float)$opts['modules']['Density'] : 1);

		// dimentions
		$config['Width']  = (isset($opts['Width'])  ? (int)$opts['Width']  : NULL);
		$config['Height'] = (isset($opts['Height']) ? (int)$opts['Height'] : NULL);

		return $config;
	}

	public function draw($data, string $symbology, array $opts = [])
	{
		$isDataMatrix = (substr($symbology, 0, 4) == "dmtx");
		if ($isDataMatrix){
			$renderer = new Matrix();
		} else {
			$renderer = new Linear();
		}

		$this->options += $this->parse_opts($opts, $isDataMatrix);

		switch ($symbology) {
			case 'upca'       : $code = (new Encoders\UPC)->upc_a_encode($data); break;
			case 'upce'       : $code = (new Encoders\UPC)->upc_e_encode($data); break;
			case 'ean13nopad' : $code = (new Encoders\UPC)->ean_13_encode($data, ' '); break;
			case 'ean13pad'   :
			case 'ean13'      :
				$code = (new Encoders\UPC)->ean_13_encode($data, '>');
				break;
			case 'ean8'       : $code = (new Encoders\UPC)->ean_8_encode($data); break;
			case 'code39'     : $code = (new Encoders\Codes)->code_39_encode($data); break;
			case 'code39ascii': $code = (new Encoders\Codes)->code_39_ascii_encode($data); break;
			case 'code93'     : $code = (new Encoders\Codes)->code_93_encode($data); break;
			case 'code93ascii': $code = (new Encoders\Codes)->code_93_ascii_encode($data); break;
			case 'code128'    : $code = (new Encoders\Codes)->code_128_encode($data, 0, false); break;
			case 'code128a'   : $code = (new Encoders\Codes)->code_128_encode($data, 1, false); break;
			case 'code128b'   : $code = (new Encoders\Codes)->code_128_encode($data, 2, false); break;
			case 'code128c'   : $code = (new Encoders\Codes)->code_128_encode($data, 3, false); break;
			case 'code128ac'  : $code = (new Encoders\Codes)->code_128_encode($data,-1, false); break;
			case 'code128bc'  : $code = (new Encoders\Codes)->code_128_encode($data,-2, false); break;
			case 'ean128'     : $code = (new Encoders\Codes)->code_128_encode($data, 0, true); break;
			case 'ean128a'    : $code = (new Encoders\Codes)->code_128_encode($data, 1, true); break;
			case 'ean128b'    : $code = (new Encoders\Codes)->code_128_encode($data, 2, true); break;
			case 'ean128c'    : $code = (new Encoders\Codes)->code_128_encode($data, 3, true); break;
			case 'ean128ac'   : $code = (new Encoders\Codes)->code_128_encode($data,-1, true); break;
			case 'ean128bc'   : $code = (new Encoders\Codes)->code_128_encode($data,-2, true); break;
			case 'codabar'    : $code = (new Encoders\Codabar)->codabar_encode($data);
			case 'itf'        :
			case 'itf14'      :
				$code = (new Encoders\ITF())->itf_encode($data);
				break;
			case 'dmtx'       :
			case 'dmtxs'      :
				$code = (new Encoders\DMTX())->dmtx_encode($data, false, false);
				break;
			case 'dmtxr'      : 
				$code = (new Encoders\DMTX())->dmtx_encode($data, true,  false);
				break;
			case 'dmtxgs1'    :
			case 'dmtxsgs1'   :
				$code = (new Encoders\DMTX())->dmtx_encode($data, false, true);
				break;
			case 'dmtxrgs1'   : 
				$code = (new Encoders\DMTX())->dmtx_encode($data, true,  true);
				break;
			default: throw pException::InvalidInput("Unknown encode method - ".$symbology);
		}

		$renderer->render($this->myPicture, $this->options, $code);
	}
}

<?php

namespace pChart\Barcodes;

use pChart\pColor;
use pChart\pException;
use pChart\Barcodes\Renderers\Linear;
use pChart\Barcodes\Renderers\Matrix;

class Barcodes {

	private $myPicture;

	function __construct(\pChart\pDraw $pChartObject)
	{
		$this->myPicture = $pChartObject;
	}

	private function parse_opts($opts, $isDataMatrix)
	{
		$config = [];

		if ($isDataMatrix){
			$config['scale']['Factor'] = 4;
			$config['padding']['All'] = 0;
		} else {
			$config['scale']['Factor'] = 1;
			$config['padding']['All'] = 10;
		}

		// label
		$config["label"] = ['Height' => 10, 'Size' => 1, 'Color' => new pColor(0), 'Skip' => FALSE, 'TTF' => NULL, 'Offset' => 0];

		if (isset($opts['label'])){
			$config["label"] = array_replace($config["label"], $opts['label']);
		}

		// bgcolor
		$config["BackgroundColor"] = (isset($opts['BackgroundColor'])) ? $opts['BackgroundColor'] : new pColor(255);

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

		// padding
		if (isset($opts['padding']['All'])) {
			$config['padding']['All'] = (int)$opts['padding']['All'];
		}
		$config['padding']['Horizontal'] = (isset($opts['padding']['Horizontal']) ? (int)$opts['padding']['Horizontal'] : $config['padding']['All']);
		$config['padding']['Vertial']	 = (isset($opts['padding']['Vertial']) 	  ? (int)$opts['padding']['Vertial'] 	: $config['padding']['All']);
		$config['padding']['Top'] 		 = (isset($opts['padding']['Top']) 	 	  ? (int)$opts['padding']['Top'] 		: $config['padding']['Vertial']);
		$config['padding']['Bottom']  	 = (isset($opts['padding']['Bottom']) 	  ? (int)$opts['padding']['Bottom']  	: $config['padding']['Vertial']);
		$config['padding']['Right'] 	 = (isset($opts['padding']['Right']) 	  ? (int)$opts['padding']['Right'] 		: $config['padding']['Horizontal']);
		$config['padding']['Left']  	 = (isset($opts['padding']['Left'])  	  ? (int)$opts['padding']['Left']  		: $config['padding']['Horizontal']);

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

	public function encode($data, string $symbology, array $opts = [])
	{
		$isDataMatrix = (substr($symbology, 0, 4) == "dmtx");
		if ($isDataMatrix){
			$renderer = new Matrix();
		} else {
			$renderer = new Linear();
		}

		switch ($symbology) {
			case 'upca'       : $code = (new Encoders\UPC)->upc_a_encode($data); break;
			case 'upce'       : $code = (new Encoders\UPC)->upc_e_encode($data); break;
			case 'ean13nopad' : $code = (new Encoders\UPC)->ean_13_encode($data, ' '); break;
			case 'ean13pad'   : $code = (new Encoders\UPC)->ean_13_encode($data, '>'); break;
			case 'ean13'      : $code = (new Encoders\UPC)->ean_13_encode($data, '>'); break;
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
			case 'itf'        : $code = (new Encoders\ITF())->itf_encode($data); break;
			case 'itf14'      : $code = (new Encoders\ITF())->itf_encode($data); break;
			case 'dmtx'       : $code = (new Encoders\DMTX())->dmtx_encode($data, false, false); break;
			case 'dmtxs'      : $code = (new Encoders\DMTX())->dmtx_encode($data, false, false); break;
			case 'dmtxr'      : $code = (new Encoders\DMTX())->dmtx_encode($data, true,  false); break;
			case 'dmtxgs1'    : $code = (new Encoders\DMTX())->dmtx_encode($data, false, true); break;
			case 'dmtxsgs1'   : $code = (new Encoders\DMTX())->dmtx_encode($data, false, true); break;
			case 'dmtxrgs1'   : $code = (new Encoders\DMTX())->dmtx_encode($data, true,  true); break;
			default: throw pException::InvalidInput("Unknown encode method - ".$symbology);
		}

		$renderer->configure($this->parse_opts($opts, $isDataMatrix));
		$renderer->render($this->myPicture, $code);
	}
}

?>
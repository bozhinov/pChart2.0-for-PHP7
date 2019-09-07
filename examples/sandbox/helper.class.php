<?php

class helper {

	public $Constants;

	function __construct()
	{
		$this->Constants = get_defined_constants(true)["user"];
	}

	function code2src(array $code)
	{
		$src = "&lt;?php\r\n\r\n";
		foreach($code as $line){
			if (is_null($line)){
				$src .= "\r\n";
			} else {
				$src .= $line."\r\n";
			}
		}

		return $src."?&gt\r\n";
	}

	function HexToColorObj(string $Hex, $alpha = NULL)
	{
		list($R, $G, $B) = $this->extractColors($Hex);

		if (is_null($alpha)){
			if (($R == $G) && ($G == $B)){
				$ret = 'new pColor('.$R.')';
			} else {
				$ret = 'new pColor('.$R.','.$G.','.$B.')';
			}
		} else {
			$ret = 'new pColor('.$R.','.$G.','.$B.','.$alpha.')';
		}

		return $ret;
	}

	function extractColors(string $Hex)
	{
		if (strlen($Hex) != 7){
			return [0,0,0];
		}

		// strip the #
		$Hex = substr($Hex, 1);

		$R = hexdec($Hex[0].$Hex[1]);
		$G = hexdec($Hex[2].$Hex[3]);
		$B = hexdec($Hex[4].$Hex[5]);

		return [$R,$G,$B];
	}

	function getConstant($Mode)
	{
		return $this->Constants[$Mode];
	}

	function dumpArray($Name, $Values)
	{
		if ($Values == []){
			return '$'.$Name.' = [];';
		}

		$Result = '$'.$Name.' = [';
		foreach ($Values as $Key => $Value){
			$Result .= chr(39).$Key.chr(39).'=>'.$this->translate($Value).', ';
		}

		return substr($Result, 0, -2)."];";
	}

	function translate($Value)
	{
		if ($Value == ""){
			return "VOID";
		}

		$pos = array_search($Value, $this->Constants);
		return ($pos != FALSE) ? $pos : $Value;
	}

	function stringify($Values)
	{
		array_walk($Values, array($this, 'toString'));

		return implode(",", $Values);
	}

	function toString(&$Value)
	{
		$Value = (is_numeric($Value) || $Value == "VOID") ? $Value : chr(34).$Value.chr(34);
	}

}

?>
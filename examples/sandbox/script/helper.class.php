<?php

class helper {

	public $Constants;

	function __construct()
	{
		$this->Constants = get_defined_constants(true)["user"];
	}

	function extractColors($Hexa)
	{
		if (strlen($Hexa) != 6){
			return [0,0,0];
		}

		$R = hexdec($Hexa[0].$Hexa[1]);
		$G = hexdec($Hexa[2].$Hexa[3]);
		$B = hexdec($Hexa[4].$Hexa[5]);

		return [$R,$G,$B];
	}

	function getConstant($Mode)
	{
		return $this->Constants[$Mode];
	}

	function dumpArray($Name, $Values)
	{
		if ($Values == []){
			return '$'.$Name.' = [];'."\r\n";
		}

		$Result = '$'.$Name.' = [';
		foreach ($Values as $Key => $Value){
			if (is_array($Value)){
				die("TODO: FIX THIS");
				$Result .= $this->dumpArray($Value); # second param missing
			} else {
				$Result .= chr(39).$Key.chr(39).'=>'.$this->translate($Value).', ';
			}
		}

		return substr($Result, 0, -2)."];\r\n";
	}

	function stringify($Values)
	{
		array_walk($Values, array($this, 'toString'));

		return implode(",", $Values);
	}

	function translate($Value)
	{
		if ($Value == ""){
			return "VOID";
		}

		if (!$Value instanceof pChart\pColor){
			$pos = array_search($Value, $this->Constants);
			return ($pos != FALSE) ? $pos : $Value;
		} else {
			return "new pColor(".$Value->R.",".$Value->G.",".$Value->B.",".$Value->Alpha.")";
		}
	}

	function toString(&$Value)
	{
		$Value = (is_numeric($Value) || $Value == "VOID") ? $Value : chr(34).$Value.chr(34);
	}

}

?>
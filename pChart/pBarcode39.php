<?php
/*
pBarcode39 - class to create barcodes (39B)

Version     : 2.2.1-dev
Made by     : Jean-Damien POGOLOTTI
Maintainedby: Momchil Bozhinov
Last Update : 01/01/2018

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/
/* pData class definition */

namespace pChart;

class pBarcode39
{
	var $Codes = [];
	var $Result;
	var $myPicture;
	var $MOD43;
	
	/* Class creator */
	function __construct($pChartObject, $EnableMOD43 = FALSE, $dbFileName = "")
	{
		
		if (!($pChartObject instanceof pDraw)){
			die("pBubble needs a pDraw object. Please check the examples.");
		}
		
		$this->myPicture = $pChartObject;
		
		$this->MOD43 = $EnableMOD43;
		
		$dbFileName = (strlen($dbFileName) > 0) ? $dbFileName : "pChart/data/39.db";
		
		if (!file_exists($dbFileName)){
			throw pException::InvalidResourcePath("Cannot find barcode database (data/39.db).");
		}

		$buffer = file_get_contents($dbFileName);
		$lines = explode(PHP_EOL, $buffer);

		foreach($lines as $line){
			$vals = explode(";", $line);
			$this->Codes[$vals[0]] = $vals[1];
		}

	}

	/* Return the projected size of a barcode */
	function getSize($TextString, array $Format = [])
	{
		$Angle = 0;
		$ShowLegend = FALSE;
		$LegendOffset = 5;
		$DrawArea = FALSE;
		$FontSize = 12;
		$Height = 30;
		
		/* Override defaults */
		extract($Format);
		
		$TextString = $this->encode39($TextString);
		$BarcodeLength = strlen($this->Result);
		$WOffset = ($DrawArea) ? 20 : 0;
		$HOffset = ($ShowLegend) ? $FontSize + $LegendOffset + $WOffset : 0;

		$X1 = cos($Angle * PI / 180) * ($WOffset + $BarcodeLength);
		$Y1 = sin($Angle * PI / 180) * ($WOffset + $BarcodeLength);
		$X2 = $X1 + cos(($Angle + 90) * PI / 180) * ($HOffset + $Height);
		$Y2 = $Y1 + sin(($Angle + 90) * PI / 180) * ($HOffset + $Height);
		$AreaWidth = max(abs($X1), abs($X2));
		$AreaHeight = max(abs($Y1), abs($Y2));
		
		return ["Width" => $AreaWidth, "Height" => $AreaHeight];
	}

	/* Create the encoded string */
	function encode39($Value)
	{
		$this->Result = "100101101101" . "0";
		$TextString = "";
		for ($i = 1; $i <= strlen($Value); $i++) {
			$CharCode = ord($this->mid($Value, $i, 1));
			if ($CharCode >= 97 && $CharCode <= 122) {
				$CharCode = $CharCode - 32;
			}

			if (isset($this->Codes[chr($CharCode) ])) {
				$this->Result = $this->Result . $this->Codes[chr($CharCode) ] . "0";
				$TextString = $TextString . chr($CharCode);
			}
		}

		if ($this->MOD43) {
			$Checksum = $this->checksum($TextString);
			$this->Result = $this->Result . $this->Codes[$Checksum] . "0";
		}

		$this->Result = $this->Result . "100101101101";
		
		return "*" . $TextString . "*";

	}

	/* Create the encoded string */
	function draw($Value, $X, $Y, array $Format = [])
	{
				
		$R = 0;
		$G = 0;
		$B = 0;
		$Alpha = 100;
		$Height = 30;
		$Angle = 0;
		$ShowLegend = FALSE;
		$LegendOffset = 5;
		$DrawArea = FALSE;
		$AreaR = isset($Format["AreaR"]) ? $Format["AreaR"] : 255;
		$AreaG = isset($Format["AreaG"]) ? $Format["AreaG"] : 255;
		$AreaB = isset($Format["AreaB"]) ? $Format["AreaB"] : 255;
		$AreaBorderR = $AreaR;
		$AreaBorderG = $AreaG;
		$AreaBorderB = $AreaB;
		
		/* Override defaults */
		extract($Format);
		
		$TextString = $this->encode39($Value);
		$BarcodeLength = strlen($this->Result);
		
		if ($DrawArea) {
			$X1 = $X + cos(($Angle - 135) * PI / 180) * 10;
			$Y1 = $Y + sin(($Angle - 135) * PI / 180) * 10;
			$X2 = $X1 + cos($Angle * PI / 180) * ($BarcodeLength + 20);
			$Y2 = $Y1 + sin($Angle * PI / 180) * ($BarcodeLength + 20);
			if ($ShowLegend) {
				$X3 = $X2 + cos(($Angle + 90) * PI / 180) * ($Height + $LegendOffset + $this->myPicture->FontSize + 10);
				$Y3 = $Y2 + sin(($Angle + 90) * PI / 180) * ($Height + $LegendOffset + $this->myPicture->FontSize + 10);
			} else {
				$X3 = $X2 + cos(($Angle + 90) * PI / 180) * ($Height + 20);
				$Y3 = $Y2 + sin(($Angle + 90) * PI / 180) * ($Height + 20);
			}

			$X4 = $X3 + cos(($Angle + 180) * PI / 180) * ($BarcodeLength + 20);
			$Y4 = $Y3 + sin(($Angle + 180) * PI / 180) * ($BarcodeLength + 20);
			$this->myPicture->drawPolygon([$X1,$Y1,$X2,$Y2,$X3,$Y3,$X4,$Y4], ["R" => $AreaR,"G" => $AreaG,"B" => $AreaB,"BorderR" => $AreaBorderR,"BorderG" => $AreaBorderG,"BorderB" => $AreaBorderB]);
		}

		for ($i = 1; $i <= $BarcodeLength; $i++) {
			if ($this->mid($this->Result, $i, 1) == 1) {
				$X1 = $X + cos($Angle * PI / 180) * $i;
				$Y1 = $Y + sin($Angle * PI / 180) * $i;
				$X2 = $X1 + cos(($Angle + 90) * PI / 180) * $Height;
				$Y2 = $Y1 + sin(($Angle + 90) * PI / 180) * $Height;
				$this->myPicture->drawLine($X1, $Y1, $X2, $Y2, ["R" => $R,"G" => $G,"B" => $B,"Alpha" => $Alpha]);
			}
		}

		if ($ShowLegend) {
			$X1 = $X + cos($Angle * PI / 180) * ($BarcodeLength / 2);
			$Y1 = $Y + sin($Angle * PI / 180) * ($BarcodeLength / 2);
			$LegendX = $X1 + cos(($Angle + 90) * PI / 180) * ($Height + $LegendOffset);
			$LegendY = $Y1 + sin(($Angle + 90) * PI / 180) * ($Height + $LegendOffset);
			$this->myPicture->drawText($LegendX, $LegendY, $TextString, ["R" => $R,"G" => $G,"B" => $B,"Alpha" => $Alpha,"Angle" => - $Angle,"Align" => TEXT_ALIGN_TOPMIDDLE]);
		}
	}

	function checksum($string)
	{
		$checksum = 0;
		$charset = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-. $/+%';
		for ($i = 0; $i < strlen($string); ++$i) {
			$checksum+= strpos($charset, $string[$i]);
		}

		return substr($charset, ($checksum % 43), 1);
	}

	function left($value, $NbChar)
	{
		return substr($value, 0, $NbChar);
	}

	function right($value, $NbChar)
	{
		return substr($value, strlen($value) - $NbChar, $NbChar);
	}

	function mid($value, $Depart, $NbChar)
	{
		return substr($value, $Depart - 1, $NbChar);
	}
}

?>
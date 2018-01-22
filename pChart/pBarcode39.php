<?php
/*
pBarcode39 - class to create barcodes (39B)

Version     : 2.3.0-dev
Made by     : Jean-Damien POGOLOTTI
Maintainedby: Momchil Bozhinov
Last Update : 01/02/2018

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart;

class pBarcode39
{
	var $Codes = [];
	var $myPicture;
	var $MOD43;
	
	/* Class creator */
	function __construct($pChartObject, bool $EnableMOD43 = FALSE, string $dbFileName = "", $UseCache = FALSE)
	{
		if (!($pChartObject instanceof pDraw)){
			die("pBarcode needs a pDraw object. Please check the examples.");
		}
		
		$this->myPicture = $pChartObject;
		
		$this->MOD43 = $EnableMOD43;
		
		$dbFileName = (strlen($dbFileName) > 0) ? $dbFileName : "pChart/data/39.db";
		
		if ($UseCache != FALSE){
			if (!file_exists($UseCache)){
				throw pException::InvalidResourcePath("BarcodeDb cache path not found");
			}
			if (substr($UseCache, -1) == "/"){
				$UseCache = substr($UseCache,0,-1);
			}
			$CachedDb = $UseCache."/barcode39-".basename($dbFileName).".php";
			if (file_exists($CachedDb)){
				require($CachedDb);
				return;
			} 
		}
		
		if (!file_exists($dbFileName)){
			throw pException::InvalidResourcePath("Cannot find barcode database (pChart/data/39.db).");
		}

		$buffer = file_get_contents($dbFileName);
		$lines = explode(PHP_EOL, $buffer);

		foreach($lines as $line){
			$vals = explode(";", $line);
			$this->Codes[$vals[0]] = $vals[1];
		}
		
		if ($UseCache != FALSE){
			file_put_contents($CachedDb,'<?php $this->Codes='.var_export($this->Codes,true).'; ?>');
		}

	}

	/* Return the projected size of a barcode */
	function getProjection(string $TextString, array $Format = [])
	{
		$Angle = 0;
		$ShowLegend = FALSE;
		$LegendOffset = 5;
		$DrawArea = FALSE;
		$FontSize = 12;
		$Height = 30;
		
		/* Override defaults */
		extract($Format);
		
		list($TextString, $Result) = $this->encode39($TextString);
		$BarcodeLength = strlen($Result);
		
		$WOffset = ($DrawArea) ? 20 : 0;
		$HOffset = ($ShowLegend) ? $FontSize + $LegendOffset + $WOffset : 0;
		
		if ($Angle == 0){
			return [$WOffset + $BarcodeLength, $Height + $HOffset];
		} else {
			$X1 = cos($Angle * PI / 180) * ($WOffset + $BarcodeLength);
			$Y1 = sin($Angle * PI / 180) * ($WOffset + $BarcodeLength);
			$X2 = $X1 + cos(($Angle + 90) * PI / 180) * ($HOffset + $Height);
			$Y2 = $Y1 + sin(($Angle + 90) * PI / 180) * ($HOffset + $Height);
			
			return [ceil(max(abs($X1), abs($X2))), ceil(max(abs($Y1), abs($Y2)))]; # "Width", "Height"
		}
	}
	
	/* Create the encoded string */
	function encode39(string $Value)
	{
		$Result = "100101101101" . "0";
		$TextString = "";
		$Arr = str_split($Value);

		foreach($Arr as $char) {
			$CharCode = ord($char);
			if ($CharCode >= 97 && $CharCode <= 122) {
				$CharCode -= 32;
			}

			if (isset($this->Codes[chr($CharCode)])) {
				$Result .= $this->Codes[chr($CharCode)] . "0";
				$TextString .= chr($CharCode);
			}
		}

		if ($this->MOD43) {
			$Checksum = $this->checksum($TextString);
			$Result .= $this->Codes[$Checksum] . "0";
		}
		
		return ["*" . $TextString . "*", $Result . "100101101101"];
	}

	/* Create the encoded string */
	function draw(string $Value, int $X, int $Y, array $Format = [])
	{
		$Color = isset($Format["Color"]) ? $Format["Color"] : new pColor(0,0,0,100);
		$Height = isset($Format["Height"]) ? $Format["Height"] : 30;
		$Angle = isset($Format["Angle"]) ? $Format["Angle"] : 0;
		$ShowLegend = isset($Format["ShowLegend"]) ? $Format["ShowLegend"] : FALSE;
		$LegendOffset = isset($Format["LegendOffset"]) ? $Format["LegendOffset"] : 5;
		$DrawArea = isset($Format["DrawArea"]) ? $Format["DrawArea"] : FALSE;
		$AreaColor = isset($Format["AreaColor"]) ? $Format["AreaColor"] : new pColor(255,255,255,$Color->Alpha);
		$AreaBorderColor = isset($Format["AreaBorderColor"]) ? $Format["AreaBorderColor"] : $AreaColor->newOne();
				
		list($TextString, $Result) = $this->encode39($Value);
		$BarcodeLength = strlen($Result);
		
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
			$this->myPicture->drawPolygon([$X1,$Y1,$X2,$Y2,$X3,$Y3,$X4,$Y4], ["Color" => $AreaColor,"BorderColor" => $AreaBorderColor]);
		}

		for ($i = 1; $i <= $BarcodeLength; $i++) {
			if (substr($Result, $i - 1, 1) == "1") {
				$X1 = $X + cos($Angle * PI / 180) * $i;
				$Y1 = $Y + sin($Angle * PI / 180) * $i;
				$X2 = $X1 + cos(($Angle + 90) * PI / 180) * $Height;
				$Y2 = $Y1 + sin(($Angle + 90) * PI / 180) * $Height;
				$this->myPicture->drawLine($X1, $Y1, $X2, $Y2, ["Color" => $Color]);
			}
		}

		if ($ShowLegend) {
			$X1 = $X + cos($Angle * PI / 180) * ($BarcodeLength / 2);
			$Y1 = $Y + sin($Angle * PI / 180) * ($BarcodeLength / 2);
			$LegendX = $X1 + cos(($Angle + 90) * PI / 180) * ($Height + $LegendOffset);
			$LegendY = $Y1 + sin(($Angle + 90) * PI / 180) * ($Height + $LegendOffset);
			$this->myPicture->drawText($LegendX, $LegendY, $TextString, ["Color" => $Color,"Angle" => - $Angle,"Align" => TEXT_ALIGN_TOPMIDDLE]);
		}
	}

	function checksum(string $string)
	{
		$checksum = 0;
		$charset = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-. $/+%';
		for ($i = 0; $i < strlen($string); ++$i) {
			$checksum+= strpos($charset, $string[$i]);
		}

		return substr($charset, ($checksum % 43), 1);
	}

}

?>
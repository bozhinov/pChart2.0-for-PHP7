<?php
/*
pBarcode - base class

Version     : 2.3.0-dev
Made by     : Jean-Damien POGOLOTTI
Maintainedby: Momchil Bozhinov
Last Update : 17/01/2019

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart\pBarcode;

class pBarcode
{
	var $myPicture;
	
	/* Class creator */
	function __construct($pChartObject)
	{
		if (!($pChartObject instanceof \pChart\pDraw)){
			die("pBarcode needs a pDraw object. Please check the examples.");
		}

		$this->myPicture = $pChartObject;
	}

	/* Return the projected size of a barcode */
	static function getProjectionEx(float $BarcodeLength, array $Format = [])
	{
		$Angle = 0;
		$ShowLegend = FALSE;
		$LegendOffset = 5;
		$DrawArea = FALSE;
		$FontSize = 12;
		$Height = 30;
		
		/* Override defaults */
		extract($Format);

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
	function drawEx(string $TextString, string $Result, int $X, int $Y, array $Format = [])
	{
		$Color = isset($Format["Color"]) ? $Format["Color"] : new \pChart\pColor(0,0,0,100);
		$Height = isset($Format["Height"]) ? $Format["Height"] : 30;
		$Angle = isset($Format["Angle"]) ? $Format["Angle"] : 0;
		$ShowLegend = isset($Format["ShowLegend"]) ? $Format["ShowLegend"] : FALSE;
		$LegendOffset = isset($Format["LegendOffset"]) ? $Format["LegendOffset"] : 5;
		$DrawArea = isset($Format["DrawArea"]) ? $Format["DrawArea"] : FALSE;
		$AreaColor = isset($Format["AreaColor"]) ? $Format["AreaColor"] : new \pChart\pColor(255,255,255,$Color->Alpha);
		$AreaBorderColor = isset($Format["AreaBorderColor"]) ? $Format["AreaBorderColor"] : $AreaColor->newOne();

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

}

?>
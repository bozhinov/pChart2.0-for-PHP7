<?php
/*
pSplit - class to draw spline split charts

Version     : 2.2.1-dev
Made by     : Jean-Damien POGOLOTTI
Maintainedby: Momchil Bozhinov
Last Update : 01/01/2018

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart;

define("TEXT_POS_TOP", 690001);
define("TEXT_POS_RIGHT", 690002);

/* pSplit class definition */
class pSplit
{
	/* Class creator */
	var $myPicture;
	
	/* Class creator */
	function __construct($pChartObject)
	{
		if (!($pChartObject instanceof pDraw)){
			die("pPie needs a pDraw object. Please check the examples.");
		}
		
		$this->myPicture = $pChartObject;
	}

	/* Create the encoded string */
	function drawSplitPath(array $Format = [])
	{
		$Spacing = isset($Format["Spacing"]) ? $Format["Spacing"] : 20;
		$TextPadding = isset($Format["TextPadding"]) ? $Format["TextPadding"] : 2;
		$TextPos = isset($Format["TextPos"]) ? $Format["TextPos"] : TEXT_POS_TOP;
		$Surrounding = isset($Format["Surrounding"]) ? $Format["Surrounding"] : NULL;
		$Force = isset($Format["Force"]) ? $Format["Force"] : 70;
		$Segments = isset($Format["Segments"]) ? $Format["Segments"] : 15;
		$FontSize = $this->myPicture->FontSize;
		$X1 = $this->myPicture->GraphAreaX1;
		$Y1 = $this->myPicture->GraphAreaY1;
		$X2 = $this->myPicture->GraphAreaX2;
		$Y2 = $this->myPicture->GraphAreaY2;
		/* Data Processing */
		$Data = $this->myPicture->myData->Data;
		$Palette = $this->myPicture->myData->Palette;
		$LabelSerie = $Data["Abscissa"];
		$DataSerie = "";
		foreach($Data["Series"] as $SerieName => $Value) {
			if ($SerieName != $LabelSerie && $DataSerie == "") {
				$DataSerie = $SerieName;
			}
		}

		$DataSerieSum = array_sum($Data["Series"][$DataSerie]["Data"]);
		$DataSerieCount = count($Data["Series"][$DataSerie]["Data"]);
		/* Scale Processing */
		if ($TextPos == TEXT_POS_RIGHT) {
			$YScale = (($Y2 - $Y1) - (($DataSerieCount + 1) * $Spacing)) / $DataSerieSum;
		} else {
			$YScale = (($Y2 - $Y1) - ($DataSerieCount * $Spacing)) / $DataSerieSum;
		}

		$LeftHeight = $DataSerieSum * $YScale;
		/* Re-compute graph width depending of the text mode choosen */
		if ($TextPos == TEXT_POS_RIGHT) {
			$MaxWidth = 0;
			foreach($Data["Series"][$LabelSerie]["Data"] as $Key => $Label) {
				$Boundardies = $this->myPicture->getTextBox(0, 0, $this->myPicture->FontName, $this->myPicture->FontSize, 0, $Label);
				if ($Boundardies[1]["X"] > $MaxWidth) {
					$MaxWidth = $Boundardies[1]["X"] + $TextPadding * 2;
				}
			}

			$X2 = $X2 - $MaxWidth;
		}

		/* Drawing */
		$LeftY = ((($Y2 - $Y1) / 2) + $Y1) - ($LeftHeight / 2);
		$RightY = $Y1;
		$VectorX = (($X2 - $X1) / 2);
		
		foreach($Data["Series"][$DataSerie]["Data"] as $Key => $Value) {
			
			$Label = (isset($Data["Series"][$LabelSerie]["Data"][$Key])) ? $Data["Series"][$LabelSerie]["Data"][$Key] : "-";
			
			$LeftY1 = $LeftY;
			$LeftY2 = $LeftY + $Value * $YScale;
			$RightY1 = $RightY + $Spacing;
			$RightY2 = $RightY + $Spacing + $Value * $YScale;;
			$Settings = array(
				"R" => $Palette[$Key]["R"],
				"G" => $Palette[$Key]["G"],
				"B" => $Palette[$Key]["B"],
				"Alpha" => $Palette[$Key]["Alpha"],
				"NoDraw" => TRUE,
				"Segments" => $Segments,
				"Surrounding" => $Surrounding
			);
			$PolyGon = [];
			$Angle = $this->myPicture->getAngle($X2, $RightY1, $X1, $LeftY1);
			$VectorX1 = cos(deg2rad($Angle + 90)) * $Force + ($X2 - $X1) / 2 + $X1;
			$VectorY1 = sin(deg2rad($Angle + 90)) * $Force + ($RightY1 - $LeftY1) / 2 + $LeftY1;
			$VectorX2 = cos(deg2rad($Angle - 90)) * $Force + ($X2 - $X1) / 2 + $X1;
			$VectorY2 = sin(deg2rad($Angle - 90)) * $Force + ($RightY1 - $LeftY1) / 2 + $LeftY1;
			$Points = $this->myPicture->drawBezier($X1, $LeftY1, $X2, $RightY1, $VectorX1, $VectorY1, $VectorX2, $VectorY2, $Settings);
			foreach($Points as $Key => $Pos) {
				$PolyGon[] = $Pos["X"];
				$PolyGon[] = $Pos["Y"];
			}

			$Angle = $this->myPicture->getAngle($X2, $RightY2, $X1, $LeftY2);
			$VectorX1 = cos(deg2rad($Angle + 90)) * $Force + ($X2 - $X1) / 2 + $X1;
			$VectorY1 = sin(deg2rad($Angle + 90)) * $Force + ($RightY2 - $LeftY2) / 2 + $LeftY2;
			$VectorX2 = cos(deg2rad($Angle - 90)) * $Force + ($X2 - $X1) / 2 + $X1;
			$VectorY2 = sin(deg2rad($Angle - 90)) * $Force + ($RightY2 - $LeftY2) / 2 + $LeftY2;
			$Points = $this->myPicture->drawBezier($X1, $LeftY2, $X2, $RightY2, $VectorX1, $VectorY1, $VectorX2, $VectorY2, $Settings);
			$Points = array_reverse($Points);
			foreach($Points as $Key => $Pos) {
				$PolyGon[] = $Pos["X"];
				$PolyGon[] = $Pos["Y"];
			}

			$this->myPicture->drawPolygon($PolyGon, $Settings);
			if ($TextPos == TEXT_POS_RIGHT) {
				$this->myPicture->drawText($X2 + $TextPadding, ($RightY2 - $RightY1) / 2 + $RightY1, $Label, ["Align" => TEXT_ALIGN_MIDDLELEFT]);
			} else {
				$this->myPicture->drawText($X2, $RightY1 - $TextPadding, $Label, ["Align" => TEXT_ALIGN_BOTTOMRIGHT]);
			}

			$LeftY = $LeftY2;
			$RightY = $RightY2;
		}
	}
}

?>
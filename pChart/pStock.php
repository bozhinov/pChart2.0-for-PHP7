<?php
/*
pStock - class to draw stock charts

Version     : 2.2.2-dev
Made by     : Jean-Damien POGOLOTTI
Maintainedby: Momchil Bozhinov
Last Update : 01/01/2018

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart;

/* pStock class definition */
class pStock
{
	var $myPicture;
	
	/* Class creator */
	function __construct($pChartObject)
	{
		if (!($pChartObject instanceof pDraw)){
			die("pPie needs a pDraw object. Please check the examples.");
		}
		
		$this->myPicture = $pChartObject;
	}

	/* Draw a stock chart */
	function drawStockChart(array $Format = [])
	{
		$SerieOpen = "Open";
		$SerieClose = "Close";
		$SerieMin = "Min";
		$SerieMax = "Max";
		$SerieMedian = NULL;
		$LineWidth = 1;
		$LineR = 0;
		$LineG = 0;
		$LineB = 0;
		$LineAlpha = 100;
		$ExtremityWidth = 1;
		$ExtremityLength = 3;
		$ExtremityR = 0;
		$ExtremityG = 0;
		$ExtremityB = 0;
		$ExtremityAlpha = 100;
		$BoxWidth = 8;
		$BoxUpR = isset($Format["BoxUpR"]) ? $Format["BoxUpR"] : 188;
		$BoxUpG = isset($Format["BoxUpG"]) ? $Format["BoxUpG"] : 224;
		$BoxUpB = isset($Format["BoxUpB"]) ? $Format["BoxUpB"] : 46;
		$BoxUpAlpha = 100;
		$BoxUpSurrounding = NULL;
		$BoxUpBorderR = $BoxUpR - 20;
		$BoxUpBorderG = $BoxUpG - 20;
		$BoxUpBorderB = $BoxUpB - 20;
		$BoxUpBorderAlpha = 100;
		$BoxDownR = isset($Format["BoxDownR"]) ? $Format["BoxDownR"] : 224;
		$BoxDownG = isset($Format["BoxDownG"]) ? $Format["BoxDownG"] : 100;
		$BoxDownB = isset($Format["BoxDownB"]) ? $Format["BoxDownB"] : 46;
		$BoxDownAlpha = 100;
		$BoxDownSurrounding = NULL;
		$BoxDownBorderR = $BoxDownR - 20;
		$BoxDownBorderG = $BoxDownG - 20;
		$BoxDownBorderB = $BoxDownB - 20;
		$BoxDownBorderAlpha = 100;
		$ShadowOnBoxesOnly = TRUE;
		$MedianR = 255;
		$MedianG = 0;
		$MedianB = 0;
		$MedianAlpha = 100;
		$RecordImageMap = FALSE;
		$ImageMapTitle = "Stock Chart";
		
		/* Override defaults */
		extract($Format);
		
		/* Data Processing */
		$Data = $this->myPicture->myData->Data["Series"];
		$Orientation = $this->myPicture->myData->Data["Orientation"];
		
		if (!isset($Data[$SerieOpen]) || !isset($Data[$SerieClose]) || !isset($Data[$SerieMin]) || !isset($Data[$SerieMax])) {
			throw pException::StockMissingSerieException();
		}
		
		if ($BoxUpSurrounding != NULL) {
			$BoxUpBorderR = $BoxUpR + $BoxUpSurrounding;
			$BoxUpBorderG = $BoxUpG + $BoxUpSurrounding;
			$BoxUpBorderB = $BoxUpB + $BoxUpSurrounding;
		}

		if ($BoxDownSurrounding != NULL) {
			$BoxDownBorderR = $BoxDownR + $BoxDownSurrounding;
			$BoxDownBorderG = $BoxDownG + $BoxDownSurrounding;
			$BoxDownBorderB = $BoxDownB + $BoxDownSurrounding;
		}

		($LineWidth != 1) AND $LineOffset = $LineWidth / 2;
		$BoxOffset = $BoxWidth / 2;

		list($XMargin, $XDivs) = $this->myPicture->myData->scaleGetXSettings();
		
		$Plots = [];
		foreach($Data[$SerieOpen]["Data"] as $Key => $Value) {
			$Point = [];
			if (isset($Data[$SerieClose]["Data"][$Key]) || isset($Data[$SerieMin]["Data"][$Key]) || isset($Data[$SerieMax]["Data"][$Key])) {
				$Point = array($Value,$Data[$SerieClose]["Data"][$Key], $Data[$SerieMin]["Data"][$Key], $Data[$SerieMax]["Data"][$Key]);
			}

			if ($SerieMedian != NULL && isset($Data[$SerieMedian]["Data"][$Key])) {
				$Point[] = $Data[$SerieMedian]["Data"][$Key];
			}

			$Plots[] = $Point;
		}
		
		if ($XDivs == 0){
			$XStep = 0;
		} else {
			if ($Orientation == SCALE_POS_LEFTRIGHT) {
				$XStep = ($this->myPicture->GraphAreaXdiff - $XMargin * 2) / $XDivs;
			} else {
				$XStep = ($this->myPicture->GraphAreaYdiff - $XMargin * 2) / $XDivs;
			}
		}

		$X = $this->myPicture->GraphAreaX1 + $XMargin;
		$Y = $this->myPicture->GraphAreaY1 + $XMargin;
		$LineSettings = ["R" => $LineR,"G" => $LineG,"B" => $LineB,"Alpha" => $LineAlpha];
		$ExtremitySettings = ["R" => $ExtremityR,"G" => $ExtremityG,"B" => $ExtremityB,	"Alpha" => $ExtremityAlpha];
		$BoxUpSettings = ["R" => $BoxUpR,"G" => $BoxUpG,"B" => $BoxUpB,"Alpha" => $BoxUpAlpha,"BorderR" => $BoxUpBorderR,"BorderG" => $BoxUpBorderG,"BorderB" => $BoxUpBorderB,"BorderAlpha" => $BoxUpBorderAlpha];
		$BoxDownSettings = ["R" => $BoxDownR,"G" => $BoxDownG,"B" => $BoxDownB,"Alpha" => $BoxDownAlpha,"BorderR" => $BoxDownBorderR,"BorderG" => $BoxDownBorderG,"BorderB" => $BoxDownBorderB,"BorderAlpha" => $BoxDownBorderAlpha];
		$MedianSettings = ["R" => $MedianR,"G" => $MedianG,"B" => $MedianB,"Alpha" => $MedianAlpha];
		
		$ImageMapColor_1 = $this->myPicture->toHTMLColor($BoxUpR, $BoxUpG, $BoxUpB);
		$ImageMapColor_2 = $this->myPicture->toHTMLColor($BoxDownR, $BoxDownG, $BoxDownB);
		
		foreach($Plots as $Key => $Points) {
			
			$PosArray = $this->myPicture->scaleComputeY($Points, $Data[$SerieOpen]["Axis"]);
			
			if ($RecordImageMap) {
				$Values = "Open :".$Data[$SerieOpen]["Data"][$Key]."<br />Close : ".$Data[$SerieClose]["Data"][$Key]."<br />Min : ".$Data[$SerieMin]["Data"][$Key]."<br />Max : ".$Data[$SerieMax]["Data"][$Key]."<br />";
				
				if ($SerieMedian != NULL) {
					$Values = $Values."Median : ".$Data[$SerieMedian]["Data"][$Key]."<br />";
				}

				$ImageMapColor = ($PosArray[0] > $PosArray[1]) ? $ImageMapColor_1 : $ImageMapColor_2;
			}
				
			if ($ShadowOnBoxesOnly) {
				$RestoreShadow = $this->myPicture->Shadow;
				$this->myPicture->Shadow = FALSE;
			}

			if ($Orientation == SCALE_POS_LEFTRIGHT) {

				if ($LineWidth == 1) {
					$this->myPicture->drawLine($X, $PosArray[2], $X, $PosArray[3], $LineSettings);
				} else {
					$this->myPicture->drawFilledRectangle($X - $LineOffset, $PosArray[2], $X + $LineOffset, $PosArray[3], $LineSettings);
				}

				if ($ExtremityWidth == 1) {
					$this->myPicture->drawLine($X - $ExtremityLength, $PosArray[2], $X + $ExtremityLength, $PosArray[2], $ExtremitySettings);
					$this->myPicture->drawLine($X - $ExtremityLength, $PosArray[3], $X + $ExtremityLength, $PosArray[3], $ExtremitySettings);
					if ($RecordImageMap) {
						$this->myPicture->addToImageMap("RECT", floor($X - $ExtremityLength).",".floor($PosArray[2]).",".floor($X + $ExtremityLength).",".floor($PosArray[3]), $ImageMapColor, $ImageMapTitle, $Values);
					}
				} else {
					$this->myPicture->drawFilledRectangle($X - $ExtremityLength, $PosArray[2], $X + $ExtremityLength, $PosArray[2] - $ExtremityWidth, $ExtremitySettings);
					$this->myPicture->drawFilledRectangle($X - $ExtremityLength, $PosArray[3], $X + $ExtremityLength, $PosArray[3] + $ExtremityWidth, $ExtremitySettings);
					if ($RecordImageMap) {
						$this->myPicture->addToImageMap("RECT", floor($X - $ExtremityLength).",".floor($PosArray[2] - $ExtremityWidth).",".floor($X + $ExtremityLength).",".floor($PosArray[3] + $ExtremityWidth), $ImageMapColor, $ImageMapTitle, $Values);
					}
				}

				($ShadowOnBoxesOnly) AND $this->myPicture->Shadow = $RestoreShadow;
				
				$this->myPicture->drawFilledRectangle($X - $BoxOffset, $PosArray[0], $X + $BoxOffset, $PosArray[1], ($PosArray[0] > $PosArray[1]) ? $BoxUpSettings : $BoxDownSettings);

				(isset($PosArray[4])) AND $this->myPicture->drawLine($X - $ExtremityLength, $PosArray[4], $X + $ExtremityLength, $PosArray[4], $MedianSettings);

				$X = $X + $XStep;
				
			} else { # if ($Orientation == SCALE_POS_TOPBOTTOM) { # Momchil: so I don't mess up the shadow
				
				if ($LineWidth == 1) {
					$this->myPicture->drawLine($PosArray[2], $Y, $PosArray[3], $Y, $LineSettings);
				} else {
					$this->myPicture->drawFilledRectangle($PosArray[2], $Y - $LineOffset, $PosArray[3], $Y + $LineOffset, $LineSettings);
				}

				if ($ExtremityWidth == 1) {
					$this->myPicture->drawLine($PosArray[2], $Y - $ExtremityLength, $PosArray[2], $Y + $ExtremityLength, $ExtremitySettings);
					$this->myPicture->drawLine($PosArray[3], $Y - $ExtremityLength, $PosArray[3], $Y + $ExtremityLength, $ExtremitySettings);
					if ($RecordImageMap) {
						$this->myPicture->addToImageMap("RECT", floor($PosArray[2]).",".floor($Y - $ExtremityLength).",".floor($PosArray[3]).",".floor($Y + $ExtremityLength), $ImageMapColor, $ImageMapTitle, $Values);
					}
				} else {
					$this->myPicture->drawFilledRectangle($PosArray[2], $Y - $ExtremityLength, $PosArray[2] - $ExtremityWidth, $Y + $ExtremityLength, $ExtremitySettings);
					$this->myPicture->drawFilledRectangle($PosArray[3], $Y - $ExtremityLength, $PosArray[3] + $ExtremityWidth, $Y + $ExtremityLength, $ExtremitySettings);
					if ($RecordImageMap) {
						$this->myPicture->addToImageMap("RECT", floor($PosArray[2] - $ExtremityWidth).",".floor($Y - $ExtremityLength).",".floor($PosArray[3] + $ExtremityWidth).",".floor($Y + $ExtremityLength), $ImageMapColor, $ImageMapTitle, $Values);
					}
				}

				($ShadowOnBoxesOnly) AND $this->myPicture->Shadow = $RestoreShadow;

				$this->myPicture->drawFilledRectangle($PosArray[0], $Y - $BoxOffset, $PosArray[1], $Y + $BoxOffset, ($PosArray[0] < $PosArray[1]) ? $BoxUpSettings : $BoxDownSettings);

				(isset($PosArray[4])) AND $this->myPicture->drawLine($PosArray[4], $Y - $ExtremityLength, $PosArray[4], $Y + $ExtremityLength, $MedianSettings);

				$Y = $Y + $XStep;
			}
		}
	}
}

?>
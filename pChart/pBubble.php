<?php
/*
pBubble - class to draw bubble charts

Version     : 2.2.1-dev
Made by     : Jean-Damien POGOLOTTI
Maintainedby: Momchil Bozhinov
Last Update : 01/01/2018

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart;

define("BUBBLE_SHAPE_ROUND", 700001);
define("BUBBLE_SHAPE_SQUARE", 700002);

/* pBubble class definition */
class pBubble
{

	var $myPicture;
	
	/* Class creator */
	function __construct($pChartObject)
	{
		if (!($pChartObject instanceof pDraw)){
			die("pBubble needs a pDraw object. Please check the examples.");
		}
		
		$this->myPicture = $pChartObject;
	}

	/* Prepare the scale */
	function bubbleScale(array $DataSeries, array $WeightSeries)
	{		
		/* Parse each data series to find the new min & max boundaries to scale */
		$NewPositiveSerie = [];
		$NewNegativeSerie = [];
		$MaxValues = 0;
		$LastPositive = 0;
		$LastNegative = 0;
		foreach($DataSeries as $Key => $SerieName) {
			$SerieWeightName = $WeightSeries[$Key];
			$this->myPicture->myData->setSerieDrawable($SerieWeightName, FALSE);
			if (count($this->myPicture->myData->Data["Series"][$SerieName]["Data"]) > $MaxValues) {
				$MaxValues = count($this->myPicture->myData->Data["Series"][$SerieName]["Data"]);
			}

			foreach($this->myPicture->myData->Data["Series"][$SerieName]["Data"] as $Key => $Value) {
				if ($Value >= 0) {
					$BubbleBounds = $Value + $this->myPicture->myData->Data["Series"][$SerieWeightName]["Data"][$Key];
					if (!isset($NewPositiveSerie[$Key])) {
						$NewPositiveSerie[$Key] = $BubbleBounds;
					} elseif ($NewPositiveSerie[$Key] < $BubbleBounds) {
						$NewPositiveSerie[$Key] = $BubbleBounds;
					}

					$LastPositive = $BubbleBounds;
				} else {
					$BubbleBounds = $Value - $this->myPicture->myData->Data["Series"][$SerieWeightName]["Data"][$Key];
					if (!isset($NewNegativeSerie[$Key])) {
						$NewNegativeSerie[$Key] = $BubbleBounds;
					} elseif ($NewNegativeSerie[$Key] > $BubbleBounds) {
						$NewNegativeSerie[$Key] = $BubbleBounds;
					}

					$LastNegative = $BubbleBounds;
				}
			}
		}

		/* Check for missing values and all the fake positive serie */
		if (count($NewPositiveSerie) > 0) //if ( $NewPositiveSerie != "" )
		{
			for ($i = 0; $i < $MaxValues; $i++) {
				if (!isset($NewPositiveSerie[$i])) {
					$NewPositiveSerie[$i] = $LastPositive;
				}
			}

			$this->myPicture->myData->addPoints($NewPositiveSerie, "BubbleFakePositiveSerie");
		}

		/* Check for missing values and all the fake negative serie */
		if (count($NewNegativeSerie) > 0) // if ( $NewNegativeSerie != "" )
		{
			for ($i = 0; $i < $MaxValues; $i++) {
				if (!isset($NewNegativeSerie[$i])) {
					$NewNegativeSerie[$i] = $LastNegative;
				}
			}

			$this->myPicture->myData->addPoints($NewNegativeSerie, "BubbleFakeNegativeSerie");
		}
	}

	function resetSeriesColors()
	{
		$Data = $this->myPicture->myData->Data;
		$Palette = $this->myPicture->myData->Palette;
		$ID = 0;
		foreach($Data["Series"] as $SerieName => $SeriesParameters) {
			if ($SeriesParameters["isDrawable"]) {
				$this->myPicture->myData->Data["Series"][$SerieName]["Color"] = [
					"R" => $Palette[$ID]["R"],
					"G" => $Palette[$ID]["G"],
					"B" => $Palette[$ID]["B"],
					"Alpha" => $Palette[$ID]["Alpha"]
				];
				$ID++;
			}
		}
	}

	/* Prepare the scale */
	function drawBubbleChart(array $DataSeries, array $WeightSeries, array $Format = [])
	{
		$ForceAlpha = VOID;
		$DrawBorder = TRUE;
		$BorderWidth = 1;
		$Shape = BUBBLE_SHAPE_ROUND;
		$Surrounding = NULL;
		$BorderR = 0;
		$BorderG = 0;
		$BorderB = 0;
		$BorderAlpha = 30;
		$RecordImageMap = FALSE;
		
		/* Override defaults */
		extract($Format);
				
		$Data = $this->myPicture->myData->Data;
		$Palette = $this->myPicture->myData->Palette;
		
		if (isset($Data["Series"]["BubbleFakePositiveSerie"])) {
			$this->myPicture->myData->setSerieDrawable("BubbleFakePositiveSerie", FALSE);
		}

		if (isset($Data["Series"]["BubbleFakeNegativeSerie"])) {
			$this->myPicture->myData->setSerieDrawable("BubbleFakeNegativeSerie", FALSE);
		}

		$this->resetSeriesColors();
		list($XMargin, $XDivs) = $this->myPicture->scaleGetXSettings();
		foreach($DataSeries as $Key => $SerieName) {
			$AxisID = $Data["Series"][$SerieName]["Axis"];
			$Mode = $Data["Axis"][$AxisID]["Display"];
			$Format = $Data["Axis"][$AxisID]["Format"];
			$Unit = $Data["Axis"][$AxisID]["Unit"];
			$SerieDescription = (isset($Data["Series"][$SerieName]["Description"])) ? $Data["Series"][$SerieName]["Description"] : $SerieName;
			$XStep = ($this->myPicture->GraphAreaX2 - $this->myPicture->GraphAreaX1 - $XMargin * 2) / $XDivs;
			$X = $this->myPicture->GraphAreaX1 + $XMargin;
			$Y = $this->myPicture->GraphAreaY1 + $XMargin;
			$Color = ["R" => $Palette[$Key]["R"],"G" => $Palette[$Key]["G"],"B" => $Palette[$Key]["B"],"Alpha" => $Palette[$Key]["Alpha"]];
			if ($ForceAlpha != VOID) {
				$Color["Alpha"] = $ForceAlpha;
			}

			if ($DrawBorder) {
				if ($BorderWidth != 1) {
					if ($Surrounding != NULL) {
						$BorderR = $Palette[$Key]["R"] + $Surrounding;
						$BorderG = $Palette[$Key]["G"] + $Surrounding;
						$BorderB = $Palette[$Key]["B"] + $Surrounding;
					} 
					if ($ForceAlpha != VOID) {
						$BorderAlpha = $ForceAlpha / 2;
					}

					$BorderColor = ["R" => $BorderR,"G" => $BorderG,"B" => $BorderB,"Alpha" => $BorderAlpha];
				} else {
					$Color["BorderAlpha"] = $BorderAlpha;
					if ($Surrounding != NULL) {
						$Color["BorderR"] = $Palette[$Key]["R"] + $Surrounding;
						$Color["BorderG"] = $Palette[$Key]["G"] + $Surrounding;
						$Color["BorderB"] = $Palette[$Key]["B"] + $Surrounding;
					} else {
						$Color["BorderR"] = $BorderR;
						$Color["BorderG"] = $BorderG;
						$Color["BorderB"] = $BorderB;
					}

					if ($ForceAlpha != VOID) {
						$Color["BorderAlpha"] = $ForceAlpha / 2;
					}
				}
			}

			foreach($Data["Series"][$SerieName]["Data"] as $iKey => $Point) {
				$Weight = $Point + $Data["Series"][$WeightSeries[$Key]]["Data"][$iKey];
				$PosArray = $this->myPicture->scaleComputeY($Point, ["AxisID" => $AxisID]);
				$WeightArray = $this->myPicture->scaleComputeY($Weight, ["AxisID" => $AxisID]);
				if ($Data["Orientation"] == SCALE_POS_LEFTRIGHT) {
					$XStep = ($XDivs == 0) ? 0 : ($this->myPicture->GraphAreaX2 - $this->myPicture->GraphAreaX1 - $XMargin * 2) / $XDivs;
					$Y = floor($PosArray);
					$CircleRadius = floor(abs($PosArray - $WeightArray) / 2);
					if ($Shape == BUBBLE_SHAPE_SQUARE) {
						if ($RecordImageMap) {
							$this->myPicture->addToImageMap("RECT", floor($X - $CircleRadius) . "," . floor($Y - $CircleRadius) . "," . floor($X + $CircleRadius) . "," . floor($Y + $CircleRadius), $this->myPicture->toHTMLColor($Palette[$Key]["R"], $Palette[$Key]["G"], $Palette[$Key]["B"]), $SerieDescription, $Data["Series"][$WeightSeries[$Key]]["Data"][$iKey]);
						}

						if ($BorderWidth != 1) {
							$this->myPicture->drawFilledRectangle($X - $CircleRadius - $BorderWidth, $Y - $CircleRadius - $BorderWidth, $X + $CircleRadius + $BorderWidth, $Y + $CircleRadius + $BorderWidth, $BorderColor);
							$this->myPicture->drawFilledRectangle($X - $CircleRadius, $Y - $CircleRadius, $X + $CircleRadius, $Y + $CircleRadius, $Color);
						} else {
							$this->myPicture->drawFilledRectangle($X - $CircleRadius, $Y - $CircleRadius, $X + $CircleRadius, $Y + $CircleRadius, $Color);
						}
					} elseif ($Shape == BUBBLE_SHAPE_ROUND) {
						if ($RecordImageMap) {
							$this->myPicture->addToImageMap("CIRCLE", floor($X) . "," . floor($Y) . "," . floor($CircleRadius), $this->myPicture->toHTMLColor($Palette[$Key]["R"], $Palette[$Key]["G"], $Palette[$Key]["B"]), $SerieDescription, $Data["Series"][$WeightSeries[$Key]]["Data"][$iKey]);
						}

						if ($BorderWidth != 1) {
							$this->myPicture->drawFilledCircle($X, $Y, $CircleRadius + $BorderWidth, $BorderColor);
							$this->myPicture->drawFilledCircle($X, $Y, $CircleRadius, $Color);
						} else {
							$this->myPicture->drawFilledCircle($X, $Y, $CircleRadius, $Color);
						}
					}

					$X = $X + $XStep;
					
				} elseif ($Data["Orientation"] == SCALE_POS_TOPBOTTOM) {
					$XStep = ($XDivs == 0) ? 0 : ($this->myPicture->GraphAreaY2 - $this->myPicture->GraphAreaY1 - $XMargin * 2) / $XDivs;
					$X = floor($PosArray);
					$CircleRadius = floor(abs($PosArray - $WeightArray) / 2);
					if ($Shape == BUBBLE_SHAPE_SQUARE) {
						if ($RecordImageMap) {
							$this->myPicture->addToImageMap("RECT", floor($X - $CircleRadius) . "," . floor($Y - $CircleRadius) . "," . floor($X + $CircleRadius) . "," . floor($Y + $CircleRadius), $this->myPicture->toHTMLColor($Palette[$Key]["R"], $Palette[$Key]["G"], $Palette[$Key]["B"]), $SerieDescription, $Data["Series"][$WeightSeries[$Key]]["Data"][$iKey]);
						}

						if ($BorderWidth != 1) {
							$this->myPicture->drawFilledRectangle($X - $CircleRadius - $BorderWidth, $Y - $CircleRadius - $BorderWidth, $X + $CircleRadius + $BorderWidth, $Y + $CircleRadius + $BorderWidth, $BorderColor);
							$this->myPicture->drawFilledRectangle($X - $CircleRadius, $Y - $CircleRadius, $X + $CircleRadius, $Y + $CircleRadius, $Color);
						} else {
							$this->myPicture->drawFilledRectangle($X - $CircleRadius, $Y - $CircleRadius, $X + $CircleRadius, $Y + $CircleRadius, $Color);
						}
					} elseif ($Shape == BUBBLE_SHAPE_ROUND) {
						if ($RecordImageMap) {
							$this->myPicture->addToImageMap("CIRCLE", floor($X) . "," . floor($Y) . "," . floor($CircleRadius), $this->myPicture->toHTMLColor($Palette[$Key]["R"], $Palette[$Key]["G"], $Palette[$Key]["B"]), $SerieDescription, $Data["Series"][$WeightSeries[$Key]]["Data"][$iKey]);
						}

						if ($BorderWidth != 1) {
							$this->myPicture->drawFilledCircle($X, $Y, $CircleRadius + $BorderWidth, $BorderColor);
							$this->myPicture->drawFilledCircle($X, $Y, $CircleRadius, $Color);
						} else {
							$this->myPicture->drawFilledCircle($X, $Y, $CircleRadius, $Color);
						}
					}

					$Y = $Y + $XStep;
				}
			}
		}
	}

	function writeBubbleLabel($SerieName, $SerieWeightName, $Points, array $Format = [])
	{
		$Data = $this->myPicture->myData->Data;
		$Palette = $this->myPicture->myData->Palette;
		
		if (!isset($Data["Series"][$SerieName]) || !isset($Data["Series"][$SerieWeightName])) {
			return 0;
		}

		$DrawPoint = isset($Format["DrawPoint"]) ? $Format["DrawPoint"] : LABEL_POINT_BOX;
		(!is_array($Points)) AND $Points = [$Points];
		
		list($XMargin, $XDivs) = $this->myPicture->scaleGetXSettings();
		$AxisID = $Data["Series"][$SerieName]["Axis"];
		$AxisMode = $Data["Axis"][$AxisID]["Display"];
		$AxisFormat = $Data["Axis"][$AxisID]["Format"];
		$AxisUnit = $Data["Axis"][$AxisID]["Unit"];
		$XStep = ($this->myPicture->GraphAreaX2 - $this->myPicture->GraphAreaX1 - $XMargin * 2) / $XDivs;
		$X = $this->myPicture->GraphAreaX1 + $XMargin;
		$Y = $this->myPicture->GraphAreaY1 + $XMargin;
		$Color = [
			"R" => $Data["Series"][$SerieName]["Color"]["R"],
			"G" => $Data["Series"][$SerieName]["Color"]["G"],
			"B" => $Data["Series"][$SerieName]["Color"]["B"],
			"Alpha" => $Data["Series"][$SerieName]["Color"]["Alpha"]
		];
		foreach($Points as $Key => $Point) {
			$Value = $Data["Series"][$SerieName]["Data"][$Point];
			$PosArray = $this->myPicture->scaleComputeY($Value, ["AxisID" => $AxisID]);
			if (isset($Data["Abscissa"]) && isset($Data["Series"][$Data["Abscissa"]]["Data"][$Point])) {
				$Abscissa = $Data["Series"][$Data["Abscissa"]]["Data"][$Point] . " : ";
			} else {
				$Abscissa = "";
			}

			$Value = $this->myPicture->scaleFormat($Value, $AxisMode, $AxisFormat, $AxisUnit);
			$Weight = $Data["Series"][$SerieWeightName]["Data"][$Point];
			$Description = (isset($Data["Series"][$SerieName]["Description"])) ? $Data["Series"][$SerieName]["Description"] : "No description";
			$Series = ["Format" => $Color,"Caption" => $Abscissa . $Value . " / " . $Weight];
			
			if ($Data["Orientation"] == SCALE_POS_LEFTRIGHT) {
				$XStep = ($XDivs == 0) ? 0 : ($this->myPicture->GraphAreaX2 - $this->myPicture->GraphAreaX1 - $XMargin * 2) / $XDivs;
				$X = floor($X + $Point * $XStep);
				$Y = floor($PosArray);
			} else {
				$YStep = ($XDivs == 0) ? 0 :($this->myPicture->GraphAreaY2 - $this->myPicture->GraphAreaY1 - $XMargin * 2) / $XDivs;
				$X = floor($PosArray);
				$Y = floor($Y + $Point * $YStep);
			}

			if ($DrawPoint == LABEL_POINT_CIRCLE) {
				$this->myPicture->drawFilledCircle($X, $Y, 3, ["R" => 255,"G" => 255,"B" => 255,"BorderR" => 0,"BorderG" => 0,"BorderB" => 0]);
			} elseif ($DrawPoint == LABEL_POINT_BOX) {
				$this->myPicture->drawFilledRectangle($X - 2, $Y - 2, $X + 2, $Y + 2, ["R" => 255,"G" => 255,"B" => 255,"BorderR" => 0,	"BorderG" => 0,	"BorderB" => 0]);
			}

			$this->myPicture->drawLabelBox($X, $Y - 3, $Description, $Series, $Format);
		}
	}
}

?>
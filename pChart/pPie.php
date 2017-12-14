<?php
/*
pPie - class to draw pie charts

Version     : 2.2.2-dev
Made by     : Jean-Damien POGOLOTTI
Maintainedby: Momchil Bozhinov
Last Update : 01/01/2018

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart;

/* Class return codes */
define("PIE_LABEL_COLOR_AUTO", 140010);
define("PIE_LABEL_COLOR_MANUAL", 140011);
define("PIE_VALUE_NATURAL", 140020);
define("PIE_VALUE_PERCENTAGE", 140021);
define("PIE_VALUE_INSIDE", 140030);
define("PIE_VALUE_OUTSIDE", 140031);

/* pPie class definition */
class pPie
{

	var $LabelPos = [];
	var $myPicture;
	
	/* Class creator */
	function __construct($pChartObject)
	{
		if (!($pChartObject instanceof pDraw)){
			die("pPie needs a pDraw object. Please check the examples.");
		}
		
		$this->myPicture = $pChartObject;
	}

	/* Draw a pie chart */
	function draw2DPie($X, $Y, array $Format = [])
	{
		$Precision = 0;
		$SecondPass = TRUE;
		$Border = FALSE;
		$BorderR = 255;
		$BorderG = 255;
		$BorderB = 255;
		$Shadow = FALSE;
		$DrawLabels = FALSE;
		$LabelStacked = FALSE;
		$LabelColor = PIE_LABEL_COLOR_MANUAL;
		$LabelR = 0;
		$LabelG = 0;
		$LabelB = 0;
		$LabelAlpha = 100;
		$WriteValues = NULL;
		$ValuePosition = PIE_VALUE_OUTSIDE;
		$ValueSuffix = "";
		$ValueR = 255;
		$ValueG = 255;
		$ValueB = 255;
		$ValueAlpha = 100;
		$RecordImageMap = FALSE;		
		$Radius = 60;
		$DataGapAngle = 0;
		$DataGapRadius = 0;
		$ValuePadding = 15;
		
		/* Override defaults */
		extract($Format);
		
		/* Data Processing */
		$Data = $this->myPicture->myData->Data;
		$Palette = $this->myPicture->myData->Palette;
		/* Do we have an abscissa serie defined? */
		if ($Data["Abscissa"] == "") {
			throw pException::PieNoAbscissaException();
		}

		/* Try to find the data serie */
		$DataSerie = "";
		foreach($Data["Series"] as $SerieName => $SerieData) {
			if ($SerieName != $Data["Abscissa"]) {
				$DataSerie = $SerieName;
			}
		}

		/* Do we have data to compute? */
		if ($DataSerie == "") {
			throw pException::PieNoDataSerieException();
		}

		/* Remove unused data */
		list($Data, $Palette) = $this->clean0Values($Data, $Palette, $DataSerie, $Data["Abscissa"]);
		/* Compute the pie sum */
		$SerieSum = $this->myPicture->myData->getSum($DataSerie);
		/* Do we have data to draw? */
		if ($SerieSum == 0) {
			throw pException::PieNoDataToDrawException();
		}

		/* Dump the real number of data to draw */
		$Values = [];
		foreach($Data["Series"][$DataSerie]["Data"] as $Key => $Value) {
			if ($Value != 0) {
				$Values[] = $Value;
			}
		}

		/* Compute the wasted angular space between series */
		$WastedAngular = (count($Values) == 1) ? 0 : count($Values) * $DataGapAngle;

		/* Compute the scale */
		$ScaleFactor = (360 - $WastedAngular) / $SerieSum;
		$RestoreShadow = $this->myPicture->Shadow;
		if ($this->myPicture->Shadow) {
			$this->myPicture->Shadow = FALSE;
			$ShadowFormat = $Format;
			$ShadowFormat["Shadow"] = TRUE;
			$this->draw2DPie($X + $this->myPicture->ShadowX, $Y + $this->myPicture->ShadowY, $ShadowFormat);
		}

		/* Draw the polygon pie elements */
		$Step = 360 / (2 * PI * $Radius);
		$Offset = 0;
		$ID = 0;
		
		foreach($Values as $Key => $Value) {
			if ($Shadow) {
				$Settings = ["R" => $this->myPicture->ShadowR,"G" => $this->myPicture->ShadowG,"B" => $this->myPicture->ShadowB,"Alpha" => $this->myPicture->Shadowa];
			} else {
				if (!isset($Palette[$ID]["R"])) {
					$Color = $this->myPicture->getRandomColor();
					$Palette[$ID] = $Color;
					$this->myPicture->myData->savePalette($ID, $Color);
				}

				$Settings = ["R" => $Palette[$ID]["R"],"G" => $Palette[$ID]["G"],"B" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
			}

			if (!$SecondPass && !$Shadow) {
				if (!$Border) {
					$Settings["Surrounding"] = 10;
				} else {
					$Settings["BorderR"] = $BorderR;
					$Settings["BorderG"] = $BorderG;
					$Settings["BorderB"] = $BorderB;
				}
			}

			$EndAngle = $Offset + ($Value * $ScaleFactor);
			($EndAngle > 360) AND $EndAngle = 360;
			
			$Angle = ($EndAngle - $Offset) / 2 + $Offset;
			if ($DataGapAngle == 0) {
				$X0 = $X;
				$Y0 = $Y;
			} else {
				$X0 = cos(($Angle - 90) * PI / 180) * $DataGapRadius + $X;
				$Y0 = sin(($Angle - 90) * PI / 180) * $DataGapRadius + $Y;
			}

			$Plots = [$X0, $Y0];
			for ($i = $Offset; $i <= $EndAngle; $i = $i + $Step) {
				$Xc = cos(($i - 90) * PI / 180) * $Radius + $X;
				$Yc = sin(($i - 90) * PI / 180) * $Radius + $Y;
				if ($SecondPass && ($i < 90)) {
					$Yc++;
				}
				
				# Momchil TODO: $i >= 90 && $i =< 180 ?

				if ($SecondPass && ($i > 180 && $i < 270)) {
					$Xc++;
				}

				if ($SecondPass && ($i >= 270)) {
					$Xc++;
					$Yc++;
				}

				$Plots[] = $Xc;
				$Plots[] = $Yc;
			}

			$this->myPicture->drawPolygon($Plots, $Settings);
			if ($RecordImageMap && !$Shadow) {
				$this->myPicture->addToImageMap("POLY", implode(",", $Plots), $this->myPicture->toHTMLColor($Palette[$ID]["R"], $Palette[$ID]["G"], $Palette[$ID]["B"]), $Data["Series"][$Data["Abscissa"]]["Data"][$Key], $Value);
			}

			if ($DrawLabels && !$Shadow && !$SecondPass) {
				if ($LabelColor == PIE_LABEL_COLOR_AUTO) {
					$Settings = ["FillR" => $Palette[$ID]["R"],"FillG" => $Palette[$ID]["G"],"FillB" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
				} else {
					$Settings = ["FillR" => $LabelR,"FillG" => $LabelG,"FillB" => $LabelB,"Alpha" => $LabelAlpha];
				}

				$Angle = ($EndAngle - $Offset) / 2 + $Offset;
				$Xc = cos(($Angle - 90) * PI / 180) * $Radius + $X;
				$Yc = sin(($Angle - 90) * PI / 180) * $Radius + $Y;
				$Label = $Data["Series"][$Data["Abscissa"]]["Data"][$Key];
				
				if ($LabelStacked) {
					$this->writePieLabel($Xc, $Yc, $Label, $Angle, $Settings, TRUE, $X, $Y, $Radius);
				} else {
					$this->writePieLabel($Xc, $Yc, $Label, $Angle, $Settings, FALSE);
				}
			}

			$Offset = $i + $DataGapAngle;
			$ID++;
		}

		/* Second pass to smooth the angles */
		if ($SecondPass) {
			$Step = 360 / (2 * PI * $Radius);
			$Offset = 0;
			$ID = 0;
			foreach($Values as $Key => $Value) {
				$FirstPoint = TRUE;
				if ($Shadow) {
					$Settings = ["R" => $this->myPicture->ShadowR,"G" => $this->myPicture->ShadowG,"B" => $this->myPicture->ShadowB,"Alpha" => $this->myPicture->Shadowa];
				} else {
					if ($Border) {
						$Settings = ["R" => $BorderR,"G" => $BorderG,"B" => $BorderB];
					} else {
						$Settings = ["R" => $Palette[$ID]["R"],"G" => $Palette[$ID]["G"],"B" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
					}
				}

				$EndAngle = $Offset + ($Value * $ScaleFactor);
				($EndAngle > 360) AND $EndAngle = 360;
				
				if ($DataGapAngle == 0) {
					$X0 = $X;
					$Y0 = $Y;
				} else {
					$Angle = ($EndAngle - $Offset) / 2 + $Offset;
					$X0 = cos(($Angle - 90) * PI / 180) * $DataGapRadius + $X;
					$Y0 = sin(($Angle - 90) * PI / 180) * $DataGapRadius + $Y;
				}

				$Plots[] = $X0;
				$Plots[] = $Y0;
				for ($i = $Offset; $i <= $EndAngle; $i = $i + $Step) {
					$Xc = cos(($i - 90) * PI / 180) * $Radius + $X;
					$Yc = sin(($i - 90) * PI / 180) * $Radius + $Y;
					if ($FirstPoint) {
						$this->myPicture->drawLine($Xc, $Yc, $X0, $Y0, $Settings);
					} 
					$FirstPoint = FALSE;
					$this->myPicture->drawAntialiasPixel($Xc, $Yc, $Settings);
				}

				$this->myPicture->drawLine($Xc, $Yc, $X0, $Y0, $Settings);
				if ($DrawLabels && !$Shadow) {
					
					if ($LabelColor == PIE_LABEL_COLOR_AUTO) {
						$Settings = ["FillR" => $Palette[$ID]["R"],"FillG" => $Palette[$ID]["G"],"FillB" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
					} else {
						$Settings = ["FillR" => $LabelR,"FillG" => $LabelG,"FillB" => $LabelB,"Alpha" => $LabelAlpha];
					}

					$Angle = ($EndAngle - $Offset) / 2 + $Offset;
					$Xc = cos(($Angle - 90) * PI / 180) * $Radius + $X;
					$Yc = sin(($Angle - 90) * PI / 180) * $Radius + $Y;
					$Label = $Data["Series"][$Data["Abscissa"]]["Data"][$Key];
					
					if ($LabelStacked) {
						$this->writePieLabel($Xc, $Yc, $Label, $Angle, $Settings, TRUE, $X, $Y, $Radius);
					} else {
						$this->writePieLabel($Xc, $Yc, $Label, $Angle, $Settings, FALSE);
					}
				}

				$Offset = $i + $DataGapAngle;
				$ID++;
			}
		}

		if ($WriteValues != NULL && !$Shadow) {

			$Offset = 0;
			$Settings = ["Align" => TEXT_ALIGN_MIDDLEMIDDLE,"R" => $ValueR,"G" => $ValueG,"B" => $ValueB,"Alpha" => $ValueAlpha];
			
			if ($ValuePosition == PIE_VALUE_OUTSIDE) {
				$Radius = $Radius + $ValuePadding;
			} else {
				$Radius = $Radius / 2;
			}
			
			foreach($Values as $Key => $Value) {
				$EndAngle = ($Value * $ScaleFactor) + $Offset;
			    ((int)$EndAngle > 360) AND $EndAngle = 0;
				$Angle = ($EndAngle - $Offset) / 2 + $Offset;
				$Angle = ($Angle - 90) * PI / 180;
				
				$Xc = cos($Angle) * $Radius + $X;
				$Yc = sin($Angle) * $Radius + $Y;

				if ($WriteValues == PIE_VALUE_PERCENTAGE) {
					$Display = round((100 / $SerieSum) * $Value, $Precision) . "%";
				} elseif ($WriteValues == PIE_VALUE_NATURAL) {
					$Display = $Value . $ValueSuffix;
				}

				$this->myPicture->drawText($Xc, $Yc, $Display, $Settings);
				$Offset = $EndAngle + $DataGapAngle;
			}
		}

		if ($DrawLabels && $LabelStacked) {
			$this->writeShiftedLabels();
		}

		$this->myPicture->Shadow = $RestoreShadow;
	}

	/* Draw a 3D pie chart */
	function draw3DPie($X, $Y, array $Format = [])
	{
		$Precision = 0;
		$SecondPass = TRUE;
		$Border = FALSE;
		$Shadow = FALSE;
		$DrawLabels = FALSE;
		$LabelStacked = FALSE;
		$LabelColor = PIE_LABEL_COLOR_MANUAL;
		$LabelR = 0;
		$LabelG = 0;
		$LabelB = 0;
		$LabelAlpha = 100;
		$WriteValues = NULL;
		$ValueSuffix = "";
		$ValueR = 255;
		$ValueG = 255;
		$ValueB = 255;
		$ValueAlpha = 100;
		$RecordImageMap = FALSE;			
		$Radius = 80;
		$SkewFactor = .5;
		$SliceHeight = 20;
		$DataGapAngle = 0;
		$DataGapRadius = 0;
		$ValuePosition = PIE_VALUE_INSIDE;
		$ValuePadding = 15;

		/* Override defaults */
		extract($Format);
		
		/* Error correction for overlaying rounded corners */
		($SkewFactor < .5) AND $SkewFactor = .5;
		
		/* Data Processing */
		$Data = $this->myPicture->myData->Data;
		$Palette = $this->myPicture->myData->Palette;
		/* Do we have an abscissa serie defined? */
		if ($Data["Abscissa"] == "") {
			throw pException::PieNoAbscissaException();
		}

		/* Try to find the data serie */
		$DataSerie = "";
		foreach($Data["Series"] as $SerieName => $SerieData) {
			if ($SerieName != $Data["Abscissa"]) {
				$DataSerie = $SerieName;
			}
		}

		/* Do we have data to compute? */
		if ($DataSerie == "") {
			throw pException::PieNoDataSerieException();
		}

		/* Remove unused data */
		list($Data, $Palette) = $this->clean0Values($Data, $Palette, $DataSerie, $Data["Abscissa"]);
		/* Compute the pie sum */
		$SerieSum = $this->myPicture->myData->getSum($DataSerie);
		/* Do we have data to draw? */
		if ($SerieSum == 0) {
			throw pException::PieNoDataToDrawException();
		}

		/* Dump the real number of data to draw */
		$Values = [];
		foreach($Data["Series"][$DataSerie]["Data"] as $Key => $Value) {
			if ($Value != 0) {
				$Values[] = $Value;
			}
		}

		/* Compute the wasted angular space between series */
		$WastedAngular = (count($Values) == 1) ? 0 : count($Values) * $DataGapAngle;

		/* Compute the scale */
		$ScaleFactor = (360 - $WastedAngular) / $SerieSum;
		$RestoreShadow = $this->myPicture->Shadow;
		if ($this->myPicture->Shadow) {
			$this->myPicture->Shadow = FALSE;
		}

		/* Draw the polygon pie elements */
		$Step = 360 / (2 * PI * $Radius);
		$Offset = 360;
		$ID = count($Values) - 1;
		$Values = array_reverse($Values);
		$Slice = 0;
		$Slices = [];
		$SliceColors = [];
		$Visible = [];
		$SliceAngle = [];
		foreach($Values as $Key => $Value) {
			if (!isset($Palette[$ID]["R"])) {
				$Color = $this->myPicture->getRandomColor();
				$Palette[$ID] = $Color;
				$this->myPicture->myData->savePalette($ID, $Color);
			}

			$Settings = ["R" => $Palette[$ID]["R"],"G" => $Palette[$ID]["G"],"B" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
			$SliceColors[$Slice] = $Settings;
			$StartAngle = $Offset;
			$EndAngle = $Offset - ($Value * $ScaleFactor);
			($EndAngle < 0) AND $EndAngle = 0;

			if ($StartAngle > 180) {
				$Visible[$Slice]["Start"] = TRUE;
			}	else {
				$Visible[$Slice]["Start"] = TRUE; # TODO
			}

			$Visible[$Slice]["End"] = ($EndAngle < 180) ? FALSE : TRUE;

			if ($DataGapAngle == 0) {
				$X0 = $X;
				$Y0 = $Y;
			} else {
				$Angle = ($EndAngle - $Offset) / 2 + $Offset;
				$X0 = cos(($Angle - 90) * PI / 180) * $DataGapRadius + $X;
				$Y0 = sin(($Angle - 90) * PI / 180) * $DataGapRadius * $SkewFactor + $Y;
			}

			$Slices[$Slice][] = $X0;
			$Slices[$Slice][] = $Y0;
			$SliceAngle[$Slice][] = 0;
			for ($i = $Offset; $i >= $EndAngle; $i = $i - $Step) {
				$Xc = cos(($i - 90) * PI / 180) * $Radius + $X;
				$Yc = sin(($i - 90) * PI / 180) * $Radius * $SkewFactor + $Y;
				(($SecondPass || $RestoreShadow) && ($i < 90)) AND $Yc++;
				(($SecondPass || $RestoreShadow) && ($i > 90 && $i < 180)) AND $Xc++;
				(($SecondPass || $RestoreShadow) && ($i > 180 && $i < 270)) AND $Xc++;
		
				if (($SecondPass || $RestoreShadow) && ($i >= 270)) {
					$Xc++;
					$Yc++;
				}

				$Slices[$Slice][] = $Xc;
				$Slices[$Slice][] = $Yc;
				$SliceAngle[$Slice][] = $i;
			}

			$Offset = $i - $DataGapAngle;
			$ID--;
			$Slice++;
		}

		/* Draw the bottom shadow if needed */
		# Momchil: this is the only place that uses this comparison
		# All use cases check if both X & Y are !=0
		# I will leave this here, but it can hit the break at pDraw->setShadow()
		if ($RestoreShadow && ($this->myPicture->ShadowX != 0 || $this->myPicture->ShadowY != 0)) {
			foreach($Slices as $SliceID => $Plots) {
				$ShadowPie = [];
				for ($i = 0; $i < count($Plots); $i = $i + 2) {
					$ShadowPie[] = $Plots[$i] + $this->myPicture->ShadowX;
					$ShadowPie[] = $Plots[$i + 1] + $this->myPicture->ShadowY;
				}

				$Settings = ["R" => $this->myPicture->ShadowR,"G" => $this->myPicture->ShadowG,"B" => $this->myPicture->ShadowB,"Alpha" => $this->myPicture->Shadowa,"NoBorder" => TRUE];
				$this->myPicture->drawPolygon($ShadowPie, $Settings);
			}

			$Step = 360 / (2 * PI * $Radius);
			$Offset = 360;
			foreach($Values as $Key => $Value) {
				$EndAngle = $Offset - ($Value * $ScaleFactor);
				if ($EndAngle < 0) {
					$EndAngle = 0;
				}

				for ($i = $Offset; $i >= $EndAngle; $i = $i - $Step) {
					$Xc = cos(($i - 90) * PI / 180) * $Radius + $X + $this->myPicture->ShadowX;
					$Yc = sin(($i - 90) * PI / 180) * $Radius * $SkewFactor + $Y + $this->myPicture->ShadowY;
					$this->myPicture->drawAntialiasPixel($Xc, $Yc, $Settings);
				}

				$Offset = $i - $DataGapAngle;
				$ID--;
			}
		}

		/* Draw the bottom pie splice */
		foreach($Slices as $SliceID => $Plots) {
			$Settings = $SliceColors[$SliceID];
			$Settings["NoBorder"] = TRUE;
			$this->myPicture->drawPolygon($Plots, $Settings);
			if ($SecondPass) {
				$Settings = $SliceColors[$SliceID];
				if ($Border) {
					$Settings["R"]+= 30;
					$Settings["G"]+= 30;
					$Settings["B"]+= 30;
				}

				if (isset($SliceAngle[$SliceID][1])) /* Empty error handling */ {
					$Angle = $SliceAngle[$SliceID][1];
					$Xc = cos(($Angle - 90) * PI / 180) * $Radius + $X;
					$Yc = sin(($Angle - 90) * PI / 180) * $Radius * $SkewFactor + $Y;
					$this->myPicture->drawLine($Plots[0], $Plots[1], $Xc, $Yc, $Settings);
					$Angle = $SliceAngle[$SliceID][count($SliceAngle[$SliceID]) - 1];
					$Xc = cos(($Angle - 90) * PI / 180) * $Radius + $X;
					$Yc = sin(($Angle - 90) * PI / 180) * $Radius * $SkewFactor + $Y;
					$this->myPicture->drawLine($Plots[0], $Plots[1], $Xc, $Yc, $Settings);
				}
			}
		}

		/* Draw the two vertical edges */
		$Slices = array_reverse($Slices);
		$SliceColors = array_reverse($SliceColors);
		foreach($Slices as $SliceID => $Plots) {
			$Settings = $SliceColors[$SliceID];
			$Settings["R"]+= 10;
			$Settings["G"]+= 10;
			$Settings["B"]+= 10;
			$Settings["NoBorder"] = TRUE;
			if ($Visible[$SliceID]["Start"] && isset($Plots[2])) /* Empty error handling */ {
				$this->myPicture->drawLine($Plots[2], $Plots[3], $Plots[2], $Plots[3] - $SliceHeight, ["R" => $Settings["R"],"G" => $Settings["G"],"B" => $Settings["B"]]);
				$Border = [$Plots[0], $Plots[1], $Plots[0], $Plots[1] - $SliceHeight, $Plots[2], $Plots[3] - $SliceHeight, $Plots[2], $Plots[3]];
				$this->myPicture->drawPolygon($Border, $Settings);
			}
		}

		$Slices = array_reverse($Slices);
		$SliceColors = array_reverse($SliceColors);
		foreach($Slices as $SliceID => $Plots) {
			$Settings = $SliceColors[$SliceID];
			$Settings["R"]+= 10;
			$Settings["G"]+= 10;
			$Settings["B"]+= 10;
			$Settings["NoBorder"] = TRUE;
			if ($Visible[$SliceID]["End"]) {
				$this->myPicture->drawLine($Plots[count($Plots) - 2], $Plots[count($Plots) - 1], $Plots[count($Plots) - 2], $Plots[count($Plots) - 1] - $SliceHeight,["R" => $Settings["R"],"G" => $Settings["G"],"B" => $Settings["B"]]);
				$Border = [$Plots[0], $Plots[1], $Plots[0], $Plots[1] - $SliceHeight, $Plots[count($Plots) - 2], $Plots[count($Plots) - 1] - $SliceHeight, $Plots[count($Plots) - 2], $Plots[count($Plots) - 1]];
				$this->myPicture->drawPolygon($Border, $Settings);
			}
		}

		/* Draw the rounded edges */
		foreach($Slices as $SliceID => $Plots) {
			$Settings = $SliceColors[$SliceID];
			$Settings["R"]+= 10;
			$Settings["G"]+= 10;
			$Settings["B"]+= 10;
			$Settings["NoBorder"] = TRUE;
			for ($j = 2; $j < count($Plots) - 2; $j = $j + 2) {
				$Angle = $SliceAngle[$SliceID][$j / 2];
				if ($Angle < 270 && $Angle > 90) {
					$Border = [$Plots[$j], $Plots[$j + 1], $Plots[$j + 2], $Plots[$j + 3], $Plots[$j + 2], $Plots[$j + 3] - $SliceHeight, $Plots[$j], $Plots[$j + 1] - $SliceHeight];
					$this->myPicture->drawPolygon($Border, $Settings);
				}
			}

			if ($SecondPass) {
				$Settings = $SliceColors[$SliceID];
				if ($Border) {
					$Settings["R"]+= 30;
					$Settings["G"]+= 30;
					$Settings["B"]+= 30;
				}

				if (isset($SliceAngle[$SliceID][1])) /* Empty error handling */ {
					$Angle = $SliceAngle[$SliceID][1];
					if ($Angle < 270 && $Angle > 90) {
						$Xc = cos(($Angle - 90) * PI / 180) * $Radius + $X;
						$Yc = sin(($Angle - 90) * PI / 180) * $Radius * $SkewFactor + $Y;
						$this->myPicture->drawLine($Xc, $Yc, $Xc, $Yc - $SliceHeight, $Settings);
					}
				}

				$Angle = $SliceAngle[$SliceID][count($SliceAngle[$SliceID]) - 1];
				if ($Angle < 270 && $Angle > 90) {
					$Xc = cos(($Angle - 90) * PI / 180) * $Radius + $X;
					$Yc = sin(($Angle - 90) * PI / 180) * $Radius * $SkewFactor + $Y;
					$this->myPicture->drawLine($Xc, $Yc, $Xc, $Yc - $SliceHeight, $Settings);
				}

				if (isset($SliceAngle[$SliceID][1]) && $SliceAngle[$SliceID][1] > 270 && $SliceAngle[$SliceID][count($SliceAngle[$SliceID]) - 1] < 270) {
					$Xc = cos((270 - 90) * PI / 180) * $Radius + $X;
					$Yc = sin((270 - 90) * PI / 180) * $Radius * $SkewFactor + $Y;
					$this->myPicture->drawLine($Xc, $Yc, $Xc, $Yc - $SliceHeight, $Settings);
				}

				if (isset($SliceAngle[$SliceID][1]) && $SliceAngle[$SliceID][1] > 90 && $SliceAngle[$SliceID][count($SliceAngle[$SliceID]) - 1] < 90) {
					$Xc = cos((0) * PI / 180) * $Radius + $X;
					$Yc = sin((0) * PI / 180) * $Radius * $SkewFactor + $Y;
					$this->myPicture->drawLine($Xc, $Yc, $Xc, $Yc - $SliceHeight, $Settings);
				}
			}
		}

		/* Draw the top splice */
		foreach($Slices as $SliceID => $Plots) {
			$Settings = $SliceColors[$SliceID];
			$Settings["R"]+= 20;
			$Settings["G"]+= 20;
			$Settings["B"]+= 20;
			$Top = [];
			for ($j = 0; $j < count($Plots); $j = $j + 2) {
				$Top[] = $Plots[$j];
				$Top[] = $Plots[$j + 1] - $SliceHeight;
			}

			$this->myPicture->drawPolygon($Top, $Settings);
			if ($RecordImageMap && !$Shadow) {
				$this->myPicture->addToImageMap("POLY", implode(",", $Top), $this->myPicture->toHTMLColor($Settings["R"], $Settings["G"], $Settings["B"]), $Data["Series"][$Data["Abscissa"]]["Data"][count($Slices) - $SliceID - 1], $Values[$SliceID]);
			}
		}

		/* Second pass to smooth the angles */
		if ($SecondPass) {
			$Step = 360 / (2 * PI * $Radius);
			$Offset = 360;
			$ID = count($Values) - 1;
			foreach($Values as $Key => $Value) {
				$FirstPoint = TRUE;
				if ($Shadow) {
					$Settings = ["R" => $this->myPicture->ShadowR,"G" => $this->myPicture->ShadowG,"B" => $this->myPicture->ShadowB,"Alpha" => $this->myPicture->Shadowa];
				} else {
					if ($Border) {
						$Settings = ["R" => $Palette[$ID]["R"] + 30,"G" => $Palette[$ID]["G"] + 30,"B" => $Palette[$ID]["B"] + 30,"Alpha" => $Palette[$ID]["Alpha"]];
					} else {
						$Settings = ["R" => $Palette[$ID]["R"],"G" => $Palette[$ID]["G"],"B" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
					}
				}

				$EndAngle = $Offset - ($Value * $ScaleFactor);
				($EndAngle < 0) AND $EndAngle = 0;
				
				if ($DataGapAngle == 0) {
					$X0 = $X;
					$Y0 = $Y - $SliceHeight;
				} else {
					$Angle = ($EndAngle - $Offset) / 2 + $Offset;
					$X0 = cos(($Angle - 90) * PI / 180) * $DataGapRadius + $X;
					$Y0 = sin(($Angle - 90) * PI / 180) * $DataGapRadius * $SkewFactor + $Y - $SliceHeight;
				}

				$Plots[] = $X0;
				$Plots[] = $Y0;
				for ($i = $Offset; $i >= $EndAngle; $i = $i - $Step) {
					$Xc = cos(($i - 90) * PI / 180) * $Radius + $X;
					$Yc = sin(($i - 90) * PI / 180) * $Radius * $SkewFactor + $Y - $SliceHeight;
					if ($FirstPoint) {
						$this->myPicture->drawLine($Xc, $Yc, $X0, $Y0, $Settings);
					} 
					$FirstPoint = FALSE;

					$this->myPicture->drawAntialiasPixel($Xc, $Yc, $Settings);
					if ($i < 270 && $i > 90) {
						$this->myPicture->drawAntialiasPixel($Xc, $Yc + $SliceHeight, $Settings);
					}
				}

				$this->myPicture->drawLine($Xc, $Yc, $X0, $Y0, $Settings);
				$Offset = $i - $DataGapAngle;
				$ID--;
			}
		}

		if ($WriteValues != NULL) {
			
			$Offset = 360;
			$Settings = ["Align" => TEXT_ALIGN_MIDDLEMIDDLE,"R" => $ValueR,"G" => $ValueG,"B" => $ValueB,"Alpha" => $ValueAlpha];
			
			foreach($Values as $Key => $Value) {
				
				$EndAngle = $Offset - ($Value * $ScaleFactor);
				($EndAngle < 0) AND $EndAngle = 0;
			
				$Angle = ($EndAngle - $Offset) / 2 + $Offset;
				$Angle = ($Angle - 90) * PI / 180;
				if ($ValuePosition == PIE_VALUE_OUTSIDE) {
					$Xc = cos($Angle) * ($Radius + $ValuePadding) + $X;
					$Yc = sin($Angle) * (($Radius * $SkewFactor) + $ValuePadding) + $Y - $SliceHeight;
				} else {
					$Xc = cos($Angle) * ($Radius) / 2 + $X;
					$Yc = sin($Angle) * ($Radius * $SkewFactor) / 2 + $Y - $SliceHeight;
				}

				if ($WriteValues == PIE_VALUE_PERCENTAGE) {
					$Display = round((100 / $SerieSum) * $Value, $Precision) . "%";
				} elseif ($WriteValues == PIE_VALUE_NATURAL) {
					$Display = $Value . $ValueSuffix;
				}

				$this->myPicture->drawText($Xc, $Yc, $Display, $Settings);
				$Offset = $EndAngle - $DataGapAngle;
			}
		}

		if ($DrawLabels) {
			$Offset = 360;
			$ID = count($Values) - 1;
			foreach($Values as $Key => $Value) {
				if ($LabelColor == PIE_LABEL_COLOR_AUTO) {
					$Settings = ["FillR" => $Palette[$ID]["R"],"FillG" => $Palette[$ID]["G"],"FillB" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
				} else {
					$Settings = ["FillR" => $LabelR,"FillG" => $LabelG,"FillB" => $LabelB,"Alpha" => $LabelAlpha];
				}

				$EndAngle = $Offset - ($Value * $ScaleFactor);
				($EndAngle < 0) AND $EndAngle = 0;
			
				$Angle = ($EndAngle - $Offset) / 2 + $Offset;
				$Xc = cos(($Angle - 90) * PI / 180) * $Radius + $X;
				$Yc = sin(($Angle - 90) * PI / 180) * $Radius * $SkewFactor + $Y - $SliceHeight;
				if (isset($Data["Series"][$Data["Abscissa"]]["Data"][$ID])) {
					$Label = $Data["Series"][$Data["Abscissa"]]["Data"][$ID];
					if ($LabelStacked) {
						$this->writePieLabel($Xc, $Yc, $Label, $Angle, $Settings, TRUE, $X, $Y, $Radius, TRUE);
					} else {
						$this->writePieLabel($Xc, $Yc, $Label, $Angle, $Settings, FALSE);
					}
				}

				$Offset = $EndAngle - $DataGapAngle;
				$ID--;
			}
		}

		if ($DrawLabels && $LabelStacked) {
			$this->writeShiftedLabels();
		}

		$this->myPicture->Shadow = $RestoreShadow;
	}
	
	function drawPieLegend($X, $Y, array $Format = [])
	{
		$FontName = $this->myPicture->FontName;
		$FontSize = $this->myPicture->FontSize;
		$FontR = $this->myPicture->FontColorR;
		$FontG = $this->myPicture->FontColorG;
		$FontB = $this->myPicture->FontColorB;
		$BoxSize = 5;
		$Margin = 5;
		$R = 200;
		$G = 200;
		$B = 200;
		$Alpha = 100;
		$BorderR = 255;
		$BorderG = 255;
		$BorderB = 255;
		$Surrounding = NULL;
		$Style = LEGEND_ROUND;
		$Mode = LEGEND_VERTICAL;
		
		/* Override defaults */
		extract($Format);
		
		if ($Surrounding != NULL) {
			$BorderR = $R + $Surrounding;
			$BorderG = $G + $Surrounding;
			$BorderB = $B + $Surrounding;
		}

		$YStep = max($this->myPicture->FontSize, $BoxSize) + 5;
		$XStep = $BoxSize + 5;
		/* Data Processing */
		$Data = $this->myPicture->myData->Data;
		$Palette = $this->myPicture->myData->Palette;
		/* Do we have an abscissa serie defined? */
		if ($Data["Abscissa"] == "") {
			throw pException::PieNoAbscissaException();
		}

		$Boundaries = ["L" => $X, "T" => $Y, "R" => 0, "B" => 0];
		$vY = $Y;
		$vX = $X;
		
		foreach($Data["Series"][$Data["Abscissa"]]["Data"] as $Key => $Value) {
			$BoxArray = $this->myPicture->getTextBox($vX + $BoxSize + 4, $vY + $BoxSize / 2, $FontName, $FontSize, 0, $Value);
			if ($Mode == LEGEND_VERTICAL) {
				($Boundaries["T"] > $BoxArray[2]["Y"] + $BoxSize / 2) 		AND $Boundaries["T"] = $BoxArray[2]["Y"] + $BoxSize / 2;
				($Boundaries["R"] < $BoxArray[1]["X"] + 2) 					AND $Boundaries["R"] = $BoxArray[1]["X"] + 2;
				($Boundaries["B"] < $BoxArray[1]["Y"] + 2 + $BoxSize / 2) 	AND $Boundaries["B"] = $BoxArray[1]["Y"] + 2 + $BoxSize / 2;
				$vY = $vY + $YStep;
				
			} elseif ($Mode == LEGEND_HORIZONTAL) {
				($Boundaries["T"] > $BoxArray[2]["Y"] + $BoxSize / 2)	 	AND $Boundaries["T"] = $BoxArray[2]["Y"] + $BoxSize / 2;
				($Boundaries["R"] < $BoxArray[1]["X"] + 2)				 	AND $Boundaries["R"] = $BoxArray[1]["X"] + 2;
				($Boundaries["B"] < $BoxArray[1]["Y"] + 2 + $BoxSize / 2) 	AND $Boundaries["B"] = $BoxArray[1]["Y"] + 2 + $BoxSize / 2;
				$vX = $Boundaries["R"] + $XStep;
			}
		}

		$vY = $vY - $YStep;
		$vX = $vX - $XStep;
		$TopOffset = $Y - $Boundaries["T"];
		if ($Boundaries["B"] - ($vY + $BoxSize) < $TopOffset) {
			$Boundaries["B"] = $vY + $BoxSize + $TopOffset;
		}

		if ($Style == LEGEND_ROUND) {
			$this->myPicture->drawRoundedFilledRectangle($Boundaries["L"] - $Margin, $Boundaries["T"] - $Margin, $Boundaries["R"] + $Margin, $Boundaries["B"] + $Margin, $Margin, array(
				"R" => $R,
				"G" => $G,
				"B" => $B,
				"Alpha" => $Alpha,
				"BorderR" => $BorderR,
				"BorderG" => $BorderG,
				"BorderB" => $BorderB
			));
			
		} elseif ($Style == LEGEND_BOX) {
			$this->myPicture->drawFilledRectangle($Boundaries["L"] - $Margin, $Boundaries["T"] - $Margin, $Boundaries["R"] + $Margin, $Boundaries["B"] + $Margin, array(
				"R" => $R,
				"G" => $G,
				"B" => $B,
				"Alpha" => $Alpha,
				"BorderR" => $BorderR,
				"BorderG" => $BorderG,
				"BorderB" => $BorderB
			));
		}

		$RestoreShadow = $this->myPicture->Shadow;
		$this->myPicture->Shadow = FALSE;
		foreach($Data["Series"][$Data["Abscissa"]]["Data"] as $Key => $Value) {
			$R = $Palette[$Key]["R"];
			$G = $Palette[$Key]["G"];
			$B = $Palette[$Key]["B"];
			$this->myPicture->drawFilledRectangle($X + 1, $Y + 1, $X + $BoxSize + 1, $Y + $BoxSize + 1, ["R" => 0,"G" => 0,"B" => 0,"Alpha" => 20]);
			$this->myPicture->drawFilledRectangle($X, $Y, $X + $BoxSize, $Y + $BoxSize, ["R" => $R,"G" => $G,"B" => $B,"Surrounding" => 20]);
			if ($Mode == LEGEND_VERTICAL) {
				$this->myPicture->drawText($X + $BoxSize + 4, $Y + $BoxSize / 2, $Value, ["R" => $FontR,"G" => $FontG,"B" => $FontB,"Align" => TEXT_ALIGN_MIDDLELEFT,"FontName" => $FontName,"FontSize" => $FontSize]);
				$Y = $Y + $YStep;
			} elseif ($Mode == LEGEND_HORIZONTAL) {
				$BoxArray = $this->myPicture->drawText($X + $BoxSize + 4, $Y + $BoxSize / 2, $Value, ["R" => $FontR,"G" => $FontG,"B" => $FontB,"Align" => TEXT_ALIGN_MIDDLELEFT,"FontName" => $FontName,"FontSize" => $FontSize]);
				$X = $BoxArray[1]["X"] + 2 + $XStep;
			}
		}

		$this->Shadow = $RestoreShadow;
	}

	/* Set the color of the specified slice */
	function setSliceColor($SliceID, array $Format = [])
	{
		$this->myPicture->myData->Palette[$SliceID] = [
			"R" => isset($Format["R"]) ? $Format["R"] : 0,
			"G" => isset($Format["G"]) ? $Format["G"] : 0,
			"B" => isset($Format["B"]) ? $Format["B"] : 0,
			"Alpha" => isset($Format["Alpha"]) ? $Format["Alpha"] : 100
		];
	}

	/* Internally used compute the label positions */
	function writePieLabel($X, $Y, $Label, $Angle, $Settings, $Stacked, $Xc = 0, $Yc = 0, $Radius = 0, $Reversed = FALSE)
	{
		$LabelOffset = 30;

		if (!$Stacked) {
			$Settings["Angle"] = 360 - $Angle;
			$Settings["Length"] = 25;
			$Settings["Size"] = 8;
			$this->myPicture->drawArrowLabel($X, $Y, " " . $Label . " ", $Settings);
		} else {
			$X2 = cos(deg2rad($Angle - 90)) * 20 + $X;
			$Y2 = sin(deg2rad($Angle - 90)) * 20 + $Y;
			$TxtPos = $this->myPicture->getTextBox($X, $Y, $this->myPicture->FontName, $this->myPicture->FontSize, 0, $Label);
			$Height = $TxtPos[0]["Y"] - $TxtPos[2]["Y"];
			$YTop = $Y2 - $Height / 2 - 2;
			$YBottom = $Y2 + $Height / 2 + 2;
			if (count($this->LabelPos) > 0) {
				$Done = FALSE;
				foreach($this->LabelPos as $Key => $Settings) {
					if (!$Done) {						
						if (($YTop >= $Settings["YTop"] && $YTop <= $Settings["YBottom"]) || ($YBottom >= $Settings["YTop"] && $YBottom <= $Settings["YBottom"])){
							
							switch (TRUE) {
								case ($Angle <= 90):
									$this->shift(0, 180, -($Height + 2), $Reversed);
									$Done = TRUE;
									break;
								case ($Angle > 90 && $Angle <= 180):
									$this->shift(0, 180, -($Height + 2), $Reversed);
									$Done = TRUE;
									break;
								case ($Angle > 180 && $Angle <= 270):
									$this->shift(180, 360, ($Height + 2), $Reversed);
									$Done = TRUE;
									break;
								case ($Angle > 270 && $Angle <= 360):
									$this->shift(180, 360, ($Height + 2), $Reversed);
									$Done = TRUE;
									break;
							}
							
						}
					}
				}
			}

			$LabelSettings = ["YTop" => $YTop,"YBottom" => $YBottom,"Label" => $Label,"Angle" => $Angle,"X1" => $X,"Y1" => $Y,"X2" => $X2,"Y2" => $Y2];
			($Angle <= 180) AND $LabelSettings["X3"] = $Xc + $Radius + $LabelOffset;
			($Angle > 180)  AND $LabelSettings["X3"] = $Xc - $Radius - $LabelOffset;

			$this->LabelPos[] = $LabelSettings;
		}
	}

	/* Internally used to shift label positions */

	function shift($StartAngle, $EndAngle, $Offset, $Reversed)
	{
		if ($Reversed) {
			$Offset = - $Offset;
		}

		foreach($this->LabelPos as $Key => $Settings) {
			if ($Settings["Angle"] > $StartAngle && $Settings["Angle"] <= $EndAngle) {
				$this->LabelPos[$Key]["YTop"] = $Settings["YTop"] + $Offset;
				$this->LabelPos[$Key]["YBottom"] = $Settings["YBottom"] + $Offset;
				$this->LabelPos[$Key]["Y2"] = $Settings["Y2"] + $Offset;
			}
		}
	}

	/* Internally used to write the re-computed labels */

	function writeShiftedLabels()
	{

		if (count($this->LabelPos) == 0) {
			return; # Momchil: example.draw2DPie.labels
		}

		foreach($this->LabelPos as $Key => $Settings) {
			$X1 = $Settings["X1"];
			$Y1 = $Settings["Y1"];
			$X2 = $Settings["X2"];
			$Y2 = $Settings["Y2"];
			$X3 = $Settings["X3"];
			$Angle = $Settings["Angle"];
			$Label = $Settings["Label"];
			$this->myPicture->drawArrow($X2, $Y2, $X1, $Y1, ["Size" => 8]);
			if ($Angle <= 180) {
				$this->myPicture->drawLine($X2, $Y2, $X3, $Y2);
				$this->myPicture->drawText($X3 + 2, $Y2, $Label, ["Align" => TEXT_ALIGN_MIDDLELEFT]);
			} else {
				$this->myPicture->drawLine($X2, $Y2, $X3, $Y2);
				$this->myPicture->drawText($X3 - 2, $Y2, $Label, ["Align" => TEXT_ALIGN_MIDDLERIGHT]);
			}
		}
	}

	/* Draw a ring chart */
	function draw2DRing($X, $Y, array $Format = [])
	{	
		$Precision = 0;
		$Border = FALSE;
		$BorderR = 255;
		$BorderG = 255;
		$BorderB = 255;
		$Shadow = FALSE;
		$DrawLabels = FALSE;
		$LabelStacked = FALSE;
		$LabelColor = PIE_LABEL_COLOR_MANUAL;
		$LabelR = 0;
		$LabelG = 0;
		$LabelB = 0;
		$LabelAlpha = 100;
		$WriteValues = NULL;
		$ValuePosition = PIE_VALUE_OUTSIDE;
		$ValueSuffix = "";
		$ValueR = 255;
		$ValueG = 255;
		$ValueB = 255;
		$ValueAlpha = 100;
		$RecordImageMap = FALSE;
		$OuterRadius = 60;
		$InnerRadius = 30;
		$BorderAlpha = 100;
		$ValuePadding = 5;
		
		/* Override defaults */
		extract($Format);

		/* Data Processing */
		$Data = $this->myPicture->myData->Data;
		$Palette = $this->myPicture->myData->Palette;
		/* Do we have an abscissa serie defined? */
		if ($Data["Abscissa"] == "") {
			throw pException::PieNoAbscissaException();
		}

		/* Try to find the data serie */
		$DataSerie = "";
		foreach($Data["Series"] as $SerieName => $SerieData) {
			if ($SerieName != $Data["Abscissa"]) {
				$DataSerie = $SerieName;
			}
		}

		/* Do we have data to compute? */
		if ($DataSerie == "") {
			throw pException::PieNoDataSerieException();
		}

		/* Remove unused data */
		list($Data, $Palette) = $this->clean0Values($Data, $Palette, $DataSerie, $Data["Abscissa"]);
		/* Compute the pie sum */
		$SerieSum = $this->myPicture->myData->getSum($DataSerie);
		/* Do we have data to draw? */
		if ($SerieSum == 0) {
			throw pException::PieNoDataToDrawException();
		}

		/* Dump the real number of data to draw */
		$Values = [];
		foreach($Data["Series"][$DataSerie]["Data"] as $Key => $Value) {
			if ($Value != 0) {
				$Values[] = $Value;
			}
		}

		/* Compute the wasted angular space between series */
		$WastedAngular = (count($Values) == 1) ? 0 : 0; # MOMCHIL TODO

		/* Compute the scale */
		$ScaleFactor = (360 - $WastedAngular) / $SerieSum;
		$RestoreShadow = $this->myPicture->Shadow;
		if ($this->myPicture->Shadow) {
			$this->myPicture->Shadow = FALSE;
			$ShadowFormat = $Format;
			$ShadowFormat["Shadow"] = TRUE;
			$this->draw2DRing($X + $this->myPicture->ShadowX, $Y + $this->myPicture->ShadowY, $ShadowFormat);
		}

		/* Draw the polygon pie elements */
		$Step = 360 / (2 * PI * $OuterRadius);
		$Offset = 0;
		$ID = 0;
		foreach($Values as $Key => $Value) {
			
			if ($Shadow) {
				$Settings = ["R" => $this->myPicture->ShadowR,"G" => $this->myPicture->ShadowG,"B" => $this->myPicture->ShadowB,"Alpha" => $this->myPicture->Shadowa];
				$BorderColor = $Settings;
			} else {
				if (!isset($Palette[$ID]["R"])) {
					$Color = $this->myPicture->getRandomColor();
					$Palette[$ID] = $Color;
					$this->myPicture->myData->savePalette($ID, $Color);
				}
				$Settings = ["R" => $Palette[$ID]["R"],"G" => $Palette[$ID]["G"],"B" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
				$BorderColor = ($Border) ? ["R" => $BorderR,"G" => $BorderG,"B" => $BorderB,"Alpha" => $BorderAlpha] : $Settings;
			}

			$Plots = [];
			$Boundaries = [];
			$AAPixels = [];
			$EndAngle = $Offset + ($Value * $ScaleFactor);
			($EndAngle > 360) AND $EndAngle = 360;
			
			for ($i = $Offset; $i <= $EndAngle; $i = $i + $Step) {
				$Xc = cos(($i - 90) * PI / 180) * $OuterRadius + $X;
				$Yc = sin(($i - 90) * PI / 180) * $OuterRadius + $Y;
				if (!isset($Boundaries[0]["X1"])) {
					$Boundaries[0]["X1"] = $Xc;
					$Boundaries[0]["Y1"] = $Yc;
				}

				$AAPixels[] = [$Xc,$Yc];
				if ($i < 90) {
					$Yc++;
				}

				if ($i > 180 && $i < 270) {
					$Xc++;
				}

				if ($i >= 270) {
					$Xc++;
					$Yc++;
				}

				$Plots[] = $Xc;
				$Plots[] = $Yc;
			}

			$Boundaries[1]["X1"] = $Xc;
			$Boundaries[1]["Y1"] = $Yc;
			$Lasti = $EndAngle;
			for ($i = $EndAngle; $i >= $Offset; $i = $i - $Step) {
				$Xc = cos(($i - 90) * PI / 180) * ($InnerRadius - 1) + $X;
				$Yc = sin(($i - 90) * PI / 180) * ($InnerRadius - 1) + $Y;
				if (!isset($Boundaries[1]["X2"])) {
					$Boundaries[1]["X2"] = $Xc;
					$Boundaries[1]["Y2"] = $Yc;
				}

				$AAPixels[] = [$Xc,$Yc];
				$Xc = cos(($i - 90) * PI / 180) * $InnerRadius + $X;
				$Yc = sin(($i - 90) * PI / 180) * $InnerRadius + $Y;
				if ($i < 90) {
					$Yc++;
				}

				if ($i > 180 && $i < 270) {
					$Xc++;
				}

				if ($i >= 270) {
					$Xc++;
					$Yc++;
				}

				$Plots[] = $Xc;
				$Plots[] = $Yc;
			}

			$Boundaries[0]["X2"] = $Xc;
			$Boundaries[0]["Y2"] = $Yc;
			/* Draw the polygon */
			$this->myPicture->drawPolygon($Plots, $Settings);
			if ($RecordImageMap && !$Shadow) {
				$this->myPicture->addToImageMap("POLY", implode(",", $Plots), $this->myPicture->toHTMLColor($Palette[$ID]["R"], $Palette[$ID]["G"], $Palette[$ID]["B"]), $Data["Series"][$Data["Abscissa"]]["Data"][$Key], $Value);
			}

			/* Smooth the edges using AA */
			foreach($AAPixels as $iKey => $Pos) {
				$this->myPicture->drawAntialiasPixel($Pos[0], $Pos[1], $BorderColor);
			}

			$this->myPicture->drawLine($Boundaries[0]["X1"], $Boundaries[0]["Y1"], $Boundaries[0]["X2"], $Boundaries[0]["Y2"], $BorderColor);
			$this->myPicture->drawLine($Boundaries[1]["X1"], $Boundaries[1]["Y1"], $Boundaries[1]["X2"], $Boundaries[1]["Y2"], $BorderColor);
			if ($DrawLabels && !$Shadow) {
				if ($LabelColor == PIE_LABEL_COLOR_AUTO) {
					$Settings = ["FillR" => $Palette[$ID]["R"],"FillG" => $Palette[$ID]["G"],"FillB" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
				} else {
					$Settings = ["FillR" => $LabelR,"FillG" => $LabelG,"FillB" => $LabelB,"Alpha" => $LabelAlpha];
				}

				$Angle = ($EndAngle - $Offset) / 2 + $Offset;
				$Xc = cos(($Angle - 90) * PI / 180) * $OuterRadius + $X;
				$Yc = sin(($Angle - 90) * PI / 180) * $OuterRadius + $Y;
				$Label = $Data["Series"][$Data["Abscissa"]]["Data"][$Key];
				if ($LabelStacked) {
					$this->writePieLabel($Xc, $Yc, $Label, $Angle, $Settings, TRUE, $X, $Y, $OuterRadius);
				} else {
					$this->writePieLabel($Xc, $Yc, $Label, $Angle, $Settings, FALSE);
				}
			}

			$Offset = $Lasti;
			$ID++;
		}

		if ($DrawLabels && $LabelStacked) {
			$this->writeShiftedLabels();
		}

		if ($WriteValues && !$Shadow) {
			$Step = 360 / (2 * PI * $OuterRadius);
			$Offset = 0;
			foreach($Values as $Key => $Value) {
				
				$EndAngle = $Offset + ($Value * $ScaleFactor);
				($EndAngle > 360) AND $EndAngle = 360;
				$Angle = $Offset + ($Value * $ScaleFactor) / 2;
				
				if ($ValuePosition == PIE_VALUE_OUTSIDE) {
					$Xc = cos(($Angle - 90) * PI / 180) * ($OuterRadius + $ValuePadding) + $X;
					$Yc = sin(($Angle - 90) * PI / 180) * ($OuterRadius + $ValuePadding) + $Y;
					($Angle >= 0 && $Angle <= 90) AND $Align = TEXT_ALIGN_BOTTOMLEFT;
					($Angle > 90 && $Angle <= 180) AND $Align = TEXT_ALIGN_TOPLEFT;
					($Angle > 180 && $Angle <= 270) AND $Align = TEXT_ALIGN_TOPRIGHT;
					($Angle > 270) AND $Align = TEXT_ALIGN_BOTTOMRIGHT;
				} else {
					$Xc = cos(($Angle - 90) * PI / 180) * (($OuterRadius - $InnerRadius) / 2 + $InnerRadius) + $X;
					$Yc = sin(($Angle - 90) * PI / 180) * (($OuterRadius - $InnerRadius) / 2 + $InnerRadius) + $Y;
					$Align = TEXT_ALIGN_MIDDLEMIDDLE;
				}

				if ($WriteValues == PIE_VALUE_PERCENTAGE) {
					$Display = round((100 / $SerieSum) * $Value, $Precision) . "%";
				} elseif ($WriteValues == PIE_VALUE_NATURAL) {
					$Display = $Value . $ValueSuffix;
				} else {
					$Label = "";
				}

				$this->myPicture->drawText($Xc, $Yc, $Display, ["Align" => $Align,"R" => $ValueR,"G" => $ValueG,"B" => $ValueB]);
				$Offset = $EndAngle;
			}
		}

		$this->myPicture->Shadow = $RestoreShadow;
	}

	function draw3DRing($X, $Y, array $Format = [])
	{
		$Precision = 0;
		$Border = FALSE;
		$Shadow = FALSE;
		$DrawLabels = FALSE;
		$LabelStacked = FALSE;
		$LabelColor = PIE_LABEL_COLOR_MANUAL;
		$LabelR = 0;
		$LabelG = 0;
		$LabelB = 0;
		$LabelAlpha = 100;
		$WriteValues = NULL;
		$ValuePosition = PIE_VALUE_OUTSIDE;
		$ValueSuffix = "";
		$ValueR = 255;
		$ValueG = 255;
		$ValueB = 255;
		$ValueAlpha = 100;
		$RecordImageMap = FALSE;				
		$OuterRadius = 100;
		$InnerRadius = 30;
		$SkewFactor = .6;
		$SliceHeight = isset($Format["SliceHeight"]) ? $Format["SliceHeight"] : 10;
		$DataGapAngle = 10;
		$DataGapRadius = 10;
		$Cf = 20;
		$WriteValues = PIE_VALUE_NATURAL;
		$ValuePadding = $SliceHeight + 15;
		
		/* Override defaults */
		extract($Format);
	
		/* Error correction for overlaying rounded corners */
		($SkewFactor < .5) AND $SkewFactor = .5;
		
		/* Data Processing */
		$Data = $this->myPicture->myData->Data;
		$Palette = $this->myPicture->myData->Palette;
		/* Do we have an abscissa serie defined? */
		if ($Data["Abscissa"] == "") {
			throw pException::PieNoAbscissaException();
		}

		/* Try to find the data serie */
		$DataSerie = "";
		foreach($Data["Series"] as $SerieName => $SerieData) {
			if ($SerieName != $Data["Abscissa"]) {
				$DataSerie = $SerieName;
			}
		}

		/* Do we have data to compute? */
		if ($DataSerie == "") {
			throw pException::PieNoDataSerieException();
		}

		/* Remove unused data */
		list($Data, $Palette) = $this->clean0Values($Data, $Palette, $DataSerie, $Data["Abscissa"]);
		/* Compute the pie sum */
		$SerieSum = $this->myPicture->myData->getSum($DataSerie);
		/* Do we have data to draw? */
		if ($SerieSum == 0) {
			throw pException::PieNoDataToDrawException();
		}

		/* Dump the real number of data to draw */
		$Values = [];
		foreach($Data["Series"][$DataSerie]["Data"] as $Key => $Value) {
			if ($Value != 0) {
				$Values[] = $Value;
			}
		}

		/* Compute the wasted angular space between series */
		$WastedAngular = (count($Values) == 1) ? 0 : count($Values) * $DataGapAngle;

		/* Compute the scale */
		$ScaleFactor = (360 - $WastedAngular) / $SerieSum;
		$RestoreShadow = $this->myPicture->Shadow;
		if ($this->myPicture->Shadow) {
			$this->myPicture->Shadow = FALSE;
		}

		/* Draw the polygon ring elements */
		$Offset = 360;
		$ID = count($Values) - 1;
		$Values = array_reverse($Values);
		$Slice = 0;
		$Slices = [];
		$SliceColors = [];
		$Visible = []; 
		
		foreach($Values as $Key => $Value) {
			
			if (!isset($Palette[$ID]["R"])) {
				$Color = $this->myPicture->getRandomColor();
				$Palette[$ID] = $Color;
				$this->myPicture->myData->savePalette($ID, $Color);
			}

			$Settings = ["R" => $Palette[$ID]["R"],"G" => $Palette[$ID]["G"],"B" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
			$SliceColors[$Slice] = $Settings;
			$StartAngle = $Offset;
			$EndAngle = $Offset - ($Value * $ScaleFactor);
			($EndAngle < 0) AND $EndAngle = 0;
			$Visible[$Slice]["Start"] = ($StartAngle > 180) ? TRUE : TRUE; # MOMCHIL TODO
			$Visible[$Slice]["End"] = ($EndAngle < 180) ? FALSE : TRUE;
			$Step = (360 / (2 * PI * $OuterRadius)) / 2;
			$OutX1 = VOID;
			$OutY1 = VOID;
			
			for ($i = $Offset; $i >= $EndAngle; $i = $i - $Step) {
				$Xc = cos(($i - 90) * PI / 180) * ($OuterRadius + $DataGapRadius - 2) + $X;
				$Yc = sin(($i - 90) * PI / 180) * ($OuterRadius + $DataGapRadius - 2) * $SkewFactor + $Y;
				$Slices[$Slice]["AA"][] = [$Xc,$Yc];
				$Xc = cos(($i - 90) * PI / 180) * ($OuterRadius + $DataGapRadius - 1) + $X;
				$Yc = sin(($i - 90) * PI / 180) * ($OuterRadius + $DataGapRadius - 1) * $SkewFactor + $Y;
				$Slices[$Slice]["AA"][] = [$Xc,$Yc];
				$Xc = cos(($i - 90) * PI / 180) * ($OuterRadius + $DataGapRadius) + $X;
				$Yc = sin(($i - 90) * PI / 180) * ($OuterRadius + $DataGapRadius) * $SkewFactor + $Y;
				$this->myPicture->drawAntialiasPixel($Xc, $Yc, $Settings);
				if ($OutX1 == VOID) {
					$OutX1 = $Xc;
					$OutY1 = $Yc;
				}

				($i < 90) AND $Yc++;
				($i > 90 && $i < 180) AND $Xc++;
				($i > 180 && $i < 270) AND $Xc++;

				if ($i >= 270) {
					$Xc++;
					$Yc++;
				}

				$Slices[$Slice]["BottomPoly"][] = floor($Xc);
				$Slices[$Slice]["BottomPoly"][] = floor($Yc);
				$Slices[$Slice]["TopPoly"][] = floor($Xc);
				$Slices[$Slice]["TopPoly"][] = floor($Yc) - $SliceHeight;
				$Slices[$Slice]["Angle"][] = $i;
			}

			$OutX2 = $Xc;
			$OutY2 = $Yc;
			$Slices[$Slice]["Angle"][] = VOID;
			$Lasti = $i;
			$Step = (360 / (2 * PI * $InnerRadius)) / 2;
			$InX1 = VOID;
			$InY1 = VOID;
			
			for ($i = $EndAngle; $i <= $Offset; $i = $i + $Step) {
				$Xc = cos(($i - 90) * PI / 180) * ($InnerRadius + $DataGapRadius - 1) + $X;
				$Yc = sin(($i - 90) * PI / 180) * ($InnerRadius + $DataGapRadius - 1) * $SkewFactor + $Y;
				$Slices[$Slice]["AA"][] = [$Xc,$Yc];
				$Xc = cos(($i - 90) * PI / 180) * ($InnerRadius + $DataGapRadius) + $X;
				$Yc = sin(($i - 90) * PI / 180) * ($InnerRadius + $DataGapRadius) * $SkewFactor + $Y;
				$Slices[$Slice]["AA"][] = [$Xc,$Yc];
				if ($InX1 == VOID) {
					$InX1 = $Xc;
					$InY1 = $Yc;
				}

				($i < 90) AND $Yc++;
				($i > 90 && $i < 180) AND $Xc++;
				($i > 180 && $i < 270) AND $Xc++;

				if ($i >= 270) {
					$Xc++;
					$Yc++;
				}

				$Slices[$Slice]["BottomPoly"][] = floor($Xc);
				$Slices[$Slice]["BottomPoly"][] = floor($Yc);
				$Slices[$Slice]["TopPoly"][] = floor($Xc);
				$Slices[$Slice]["TopPoly"][] = floor($Yc) - $SliceHeight;
				$Slices[$Slice]["Angle"][] = $i;
			}

			$InX2 = $Xc;
			$InY2 = $Yc;
			$Slices[$Slice]["InX1"] = $InX1;
			$Slices[$Slice]["InY1"] = $InY1;
			$Slices[$Slice]["InX2"] = $InX2;
			$Slices[$Slice]["InY2"] = $InY2;
			$Slices[$Slice]["OutX1"] = $OutX1;
			$Slices[$Slice]["OutY1"] = $OutY1;
			$Slices[$Slice]["OutX2"] = $OutX2;
			$Slices[$Slice]["OutY2"] = $OutY2;
			$Offset = $Lasti - $DataGapAngle;
			$ID--;
			$Slice++;
		}

		/* Draw the bottom pie splice */
		foreach($Slices as $SliceID => $Plots) {
			$Settings = $SliceColors[$SliceID];
			$Settings["NoBorder"] = TRUE;
			$this->myPicture->drawPolygon($Plots["BottomPoly"], $Settings);
			foreach($Plots["AA"] as $Key => $Pos){
				$this->myPicture->drawAntialiasPixel($Pos[0], $Pos[1], $Settings);
			}
			$this->myPicture->drawLine($Plots["InX1"], $Plots["InY1"], $Plots["OutX2"], $Plots["OutY2"], $Settings);
			$this->myPicture->drawLine($Plots["InX2"], $Plots["InY2"], $Plots["OutX1"], $Plots["OutY1"], $Settings);
		}

		$Slices = array_reverse($Slices);
		$SliceColors = array_reverse($SliceColors);
		/* Draw the vertical edges (semi-visible) */
		foreach($Slices as $SliceID => $Plots) {
			$Settings = $SliceColors[$SliceID];
			$Settings["NoBorder"] = TRUE;
			$Settings["R"] = $Settings["R"] + $Cf;
			$Settings["G"] = $Settings["G"] + $Cf;
			$Settings["B"] = $Settings["B"] + $Cf;
			$StartAngle = $Plots["Angle"][0];
			foreach($Plots["Angle"] as $Key => $Angle) {
				if ($Angle == VOID) {
					$EndAngle = $Plots["Angle"][$Key - 1];
				}
			}

			if ($StartAngle >= 270 || $StartAngle <= 90) {
				$this->myPicture->drawLine($Plots["OutX1"], $Plots["OutY1"], $Plots["OutX1"], $Plots["OutY1"] - $SliceHeight, $Settings);
			}

			if ($StartAngle >= 270 || $StartAngle <= 90) {
				$this->myPicture->drawLine($Plots["OutX2"], $Plots["OutY2"], $Plots["OutX2"], $Plots["OutY2"] - $SliceHeight, $Settings);
			}

			$this->myPicture->drawLine($Plots["InX1"], $Plots["InY1"], $Plots["InX1"], $Plots["InY1"] - $SliceHeight, $Settings);
			$this->myPicture->drawLine($Plots["InX2"], $Plots["InY2"], $Plots["InX2"], $Plots["InY2"] - $SliceHeight, $Settings);
		}

		/* Draw the inner vertical slices */
		foreach($Slices as $SliceID => $Plots) {
			
			$Settings = $SliceColors[$SliceID];
			$Settings["NoBorder"] = TRUE;
			$Settings["R"] = $Settings["R"] + $Cf;
			$Settings["G"] = $Settings["G"] + $Cf;
			$Settings["B"] = $Settings["B"] + $Cf;
			$Outer = TRUE;
			$Inner = FALSE;
			$InnerPlotsA = [];
			$InnerPlotsB = [];
			
			foreach($Plots["Angle"] as $ID => $Angle) {
				if ($Angle == VOID) {
					$Outer = FALSE;
					$Inner = TRUE;
				} elseif ($Inner) {
					if (($Angle < 90 || $Angle > 270) && isset($Plots["BottomPoly"][$ID * 2])) {
						$Xo = $Plots["BottomPoly"][$ID * 2];
						$Yo = $Plots["BottomPoly"][$ID * 2 + 1];
						$InnerPlotsA += [$Xo,$Yo,$Xo,$Yo - $SliceHeight];

					}
				}
			}

			(count($InnerPlotsA) > 0) AND $this->myPicture->drawPolygon(array_merge($InnerPlotsA, $this->arrayReverse($InnerPlotsB)), $Settings);
			
		}

		/* Draw the splice top and left poly */
		foreach($Slices as $SliceID => $Plots) {
			$Settings = $SliceColors[$SliceID];
			$Settings["NoBorder"] = TRUE;
			$Settings["R"] = $Settings["R"] + $Cf * 1.5;
			$Settings["G"] = $Settings["G"] + $Cf * 1.5;
			$Settings["B"] = $Settings["B"] + $Cf * 1.5;
			$StartAngle = $Plots["Angle"][0];
			foreach($Plots["Angle"] as $Key => $Angle) {
				if ($Angle == VOID) {
					$EndAngle = $Plots["Angle"][$Key - 1];
				}
			}

			if ($StartAngle < 180) {
				$Points = [$Plots["InX2"], $Plots["InY2"], $Plots["InX2"], $Plots["InY2"] - $SliceHeight, $Plots["OutX1"], $Plots["OutY1"] - $SliceHeight, $Plots["OutX1"], $Plots["OutY1"]];
				$this->myPicture->drawPolygon($Points, $Settings);
			}

			if ($EndAngle > 180) {
				$Points = [$Plots["InX1"], $Plots["InY1"], $Plots["InX1"], $Plots["InY1"] - $SliceHeight, $Plots["OutX2"], $Plots["OutY2"] - $SliceHeight, $Plots["OutX2"], $Plots["OutY2"]];
				$this->myPicture->drawPolygon($Points, $Settings);
			}
		}

		/* Draw the vertical edges (visible) */
		foreach($Slices as $SliceID => $Plots) {
			$Settings = $SliceColors[$SliceID];
			$Settings["NoBorder"] = TRUE;
			$Settings["R"] = $Settings["R"] + $Cf;
			$Settings["G"] = $Settings["G"] + $Cf;
			$Settings["B"] = $Settings["B"] + $Cf;
			$StartAngle = $Plots["Angle"][0];
			foreach($Plots["Angle"] as $Key => $Angle) {
				if ($Angle == VOID) {
					$EndAngle = $Plots["Angle"][$Key - 1];
				}
			}

			if ($StartAngle <= 270 && $StartAngle >= 90) {
				$this->myPicture->drawLine($Plots["OutX1"], $Plots["OutY1"], $Plots["OutX1"], $Plots["OutY1"] - $SliceHeight, $Settings);
			}

			if ($EndAngle <= 270 && $EndAngle >= 90) {
				$this->myPicture->drawLine($Plots["OutX2"], $Plots["OutY2"], $Plots["OutX2"], $Plots["OutY2"] - $SliceHeight, $Settings);
			}
		}

		/* Draw the outer vertical slices */
		foreach($Slices as $SliceID => $Plots) {
			
			$Settings = $SliceColors[$SliceID];
			$Settings["NoBorder"] = TRUE;
			$Settings["R"] = $Settings["R"] + $Cf;
			$Settings["G"] = $Settings["G"] + $Cf;
			$Settings["B"] = $Settings["B"] + $Cf;
			$Outer = TRUE;
			$Inner = FALSE;
			$OuterPlotsA = [];
			$OuterPlotsB = []; 
			
			foreach($Plots["Angle"] as $ID => $Angle) {
				if ($Angle == VOID) {
					$Outer = FALSE;
					$Inner = TRUE;
				} elseif ($Outer) {
					if (($Angle > 90 && $Angle < 270) && isset($Plots["BottomPoly"][$ID * 2])) {
						$Xo = $Plots["BottomPoly"][$ID * 2];
						$Yo = $Plots["BottomPoly"][$ID * 2 + 1];
						$OuterPlotsA[] = $Xo;
						$OuterPlotsA[] = $Yo;
						$OuterPlotsB[] = $Xo;
						$OuterPlotsB[] = $Yo - $SliceHeight;
					}
				}
			}

			(count($OuterPlotsA) > 0) AND $this->myPicture->drawPolygon(array_merge($OuterPlotsA, $this->arrayReverse($OuterPlotsB)), $Settings);
			
		}

		$Slices = array_reverse($Slices);
		$SliceColors = array_reverse($SliceColors);
		/* Draw the top pie splice */
		foreach($Slices as $SliceID => $Plots) {
			$Settings = $SliceColors[$SliceID];
			$Settings["NoBorder"] = TRUE;
			$Settings["R"] = $Settings["R"] + $Cf * 2;
			$Settings["G"] = $Settings["G"] + $Cf * 2;
			$Settings["B"] = $Settings["B"] + $Cf * 2;
			$this->myPicture->drawPolygon($Plots["TopPoly"], $Settings);
			if ($RecordImageMap) {
				$this->myPicture->addToImageMap("POLY", implode(",", $Plots["TopPoly"]), $this->myPicture->toHTMLColor($Settings["R"], $Settings["G"], $Settings["B"]), $Data["Series"][$Data["Abscissa"]]["Data"][$SliceID], $Data["Series"][$DataSerie]["Data"][count($Slices) - $SliceID - 1]);
			}

			foreach($Plots["AA"] as $Key => $Pos) {
				$this->myPicture->drawAntialiasPixel($Pos[0], $Pos[1] - $SliceHeight, $Settings);
			}
			$this->myPicture->drawLine($Plots["InX1"], $Plots["InY1"] - $SliceHeight, $Plots["OutX2"], $Plots["OutY2"] - $SliceHeight, $Settings);
			$this->myPicture->drawLine($Plots["InX2"], $Plots["InY2"] - $SliceHeight, $Plots["OutX1"], $Plots["OutY1"] - $SliceHeight, $Settings);
		}

		if ($DrawLabels) {
			$Offset = 360;
			foreach($Values as $Key => $Value) {

				$EndAngle = $Offset - ($Value * $ScaleFactor);
				($EndAngle < 0) AND $EndAngle = 0;
			
				if ($LabelColor == PIE_LABEL_COLOR_AUTO) {
					$Settings = ["FillR" => $Palette[$ID]["R"],"FillG" => $Palette[$ID]["G"],"FillB" => $Palette[$ID]["B"],"Alpha" => $Palette[$ID]["Alpha"]];
				} else {
					$Settings = ["FillR" => $LabelR,"FillG" => $LabelG,"FillB" => $LabelB,"Alpha" => $LabelAlpha];
				}

				$Angle = ($EndAngle - $Offset) / 2 + $Offset;
				$Xc = cos(($Angle - 90) * PI / 180) * ($OuterRadius + $DataGapRadius) + $X;
				$Yc = sin(($Angle - 90) * PI / 180) * ($OuterRadius + $DataGapRadius) * $SkewFactor + $Y;
				if ($WriteValues == PIE_VALUE_PERCENTAGE) {
					$Label = $Display = round((100 / $SerieSum) * $Value, $Precision) . "%";
				} elseif ($WriteValues == PIE_VALUE_NATURAL) {
					$Label = $Data["Series"][$Data["Abscissa"]]["Data"][$Key];
				} else {
					$Label = "";
				}

				if ($LabelStacked) {
					$this->writePieLabel($Xc, $Yc - $SliceHeight, $Label, $Angle, $Settings, TRUE, $X, $Y, $OuterRadius);
				} else {
					$this->writePieLabel($Xc, $Yc - $SliceHeight, $Label, $Angle, $Settings, FALSE);
				}

				$Offset = $EndAngle - $DataGapAngle;
				$ID--;
			}
		}

		if ($DrawLabels && $LabelStacked) {
			$this->writeShiftedLabels();
		}

		$this->myPicture->Shadow = $RestoreShadow;
	}

	/* Reverse an array */
	function arrayReverse($Plots) # Not really reversing it
	{
		$Result = [];
		for ($i = count($Plots) - 1; $i >= 0; $i = $i - 2) {
			$Result[] = $Plots[$i - 1];
			$Result[] = $Plots[$i];
		}

		return $Result;
	}

	/* Remove unused series & values */
	function clean0Values($Data, $Palette, $DataSerie, $AbscissaSerie)
	{
		$NewPalette = [];
		$NewData = [];
		$NewAbscissa = [];
		/* Remove unused series */
		foreach($Data["Series"] as $SerieName => $SerieSettings) {
			if ($SerieName != $DataSerie && $SerieName != $AbscissaSerie) {
				unset($Data["Series"][$SerieName]);
			}
		}

		/* Remove NULL values */
		foreach($Data["Series"][$DataSerie]["Data"] as $Key => $Value) {
			if ($Value != 0) {
				$NewData[] = $Value;
				$NewAbscissa[] = $Data["Series"][$AbscissaSerie]["Data"][$Key];
				if (isset($Palette[$Key])) {
					$NewPalette[] = $Palette[$Key];
				}
			}
		}

		$Data["Series"][$DataSerie]["Data"] = $NewData;
		$Data["Series"][$AbscissaSerie]["Data"] = $NewAbscissa;
		
		return [$Data,$NewPalette];
	}

}

?>
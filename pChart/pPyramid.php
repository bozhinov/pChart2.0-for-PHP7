<?php
/*
pPyramid - class to draw pyramids

Version     : 0.0.1-dev
Made by     : Momchil Bozhinov
Last Update : 01/02/2019

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart;

/* pRadar class definition */
class pPyramid
{
	/* Class creator */
	var $myPicture;
	
	/* Class creator */
	function __construct($pChartObject)
	{
		if (!($pChartObject instanceof pDraw)){
			die("pRadar needs a pDraw object. Please check the examples.");
		}
		
		$this->myPicture = $pChartObject;
	}

	function drawPyramid($X, $Y, $Base, $Height, $NumSegments = 4, array $Format = []){

		$Color = isset($Format["Color"]) ? $Format["Color"] : new pColor(0);
		$Offset = isset($Format["Offset"]) ? $Format["Offset"] : 5;

		# Account for the combined heights of the offsets
		$h = ($Height - (($NumSegments - 1) * $Offset)) / $NumSegments;

		for($i=0; $i<$NumSegments; $i++){
			
			if ($i != 0){
				$Base -= (2 * $h);
			}

			$Xi = $X + ($h * $i);
			$Yi = $Y - ($h * $i);
			$Oi = ($Offset * $i);
			
			$Points = [
					$Xi + $Oi, $Yi - $Oi,
					$Xi - $Oi + $Base, $Yi - $Oi,
					$Xi + $Base - $h - $Oi, $Yi - $h - $Oi,
					$Xi + $Oi + $h, $Yi - $h - $Oi,
					$Xi + $Oi, $Yi - $Oi
				];

			#print_r($Points);
			$this->myPicture->drawPolygon($Points, ["Color"=> $Color,"NoFill" => TRUE]);
		}

	}

}

?>
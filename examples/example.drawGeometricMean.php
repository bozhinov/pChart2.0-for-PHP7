<?php   
/* CAT:Mathematical */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
for($i=0;$i<=20;$i++) 
{ 
	$myPicture->myData->addPoints(rand(10,30)+$i,"Probe 1"); 
}
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->setAbscissaName("Samples");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(150,35,"Average temperature",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,680,200);

/* Draw the scale */
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Turn on shadows */
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

/* Draw the line chart */
(new pCharts($myPicture))->drawPlotChart();

/* Draw the standard mean and the geometric one */
$Mean          = $myPicture->myData->getSerieAverage("Probe 1");
$GeometricMean = $myPicture->myData->getGeometricMean("Probe 1");
$myPicture->drawThreshold($GeometricMean,array("WriteCaption"=>TRUE,"Caption"=>"Geometric mean"));
$myPicture->drawThreshold($Mean,array("WriteCaption"=>TRUE,"Caption"=>"Mean","CaptionAlign"=>CAPTION_RIGHT_BOTTOM));

/* Write the computed values */
$myPicture->drawText(550,20,"Arithmetic average : ".round($Mean,2));
$myPicture->drawText(550,30,"Geometric Mean : ".round($GeometricMean,2));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.geometricMean.png");

?>
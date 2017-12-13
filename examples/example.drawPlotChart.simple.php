<?php   
/* CAT:Plot chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
for($i=0;$i<=20;$i++) { 
	$myPicture->myData->addPoints(rand(0,20),"Probe 1"); 
}
for($i=0;$i<=20;$i++) { 
	$myPicture->myData->addPoints(rand(0,20),"Probe 2"); 
}
$myPicture->myData->setSerieShape("Probe 1",SERIE_SHAPE_FILLEDTRIANGLE); 
$myPicture->myData->setSerieWeight("Probe 1",2);
$myPicture->myData->setSerieShape("Probe 2",SERIE_SHAPE_FILLEDSQUARE);
$myPicture->myData->setAxisName(0,"Temperatures");

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
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

/* Draw the line chart */
(new pCharts($myPicture))->drawPlotChart();

/* Write the chart legend */
$myPicture->drawLegend(580,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawPlotChart.simple.png");

?>
<?php   
/* CAT:Stacked chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([4,0,0,12,8,3,0,12,8],"Frontend #1");
$myPicture->myData->addPoints([3,12,15,8,5,5,12,15,8],"Frontend #2");
$myPicture->myData->addPoints([2,7,5,18,19,22,7,5,18],"Frontend #3");
$myPicture->myData->setAxisName(0,"Average Usage");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

/* Normalize the data series to 100% */
$myPicture->myData->normalize(100,"%");

$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,  ["StartColor"=>new pColor(240,240,240,100), "EndColor"=>new pColor(180,180,180,100)]);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,["StartColor"=>new pColor(240,240,240,100), "EndColor"=>new pColor(180,180,180,20)]);

/* Set the default font properties */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Draw the scale and the chart */ 
$myPicture->setGraphArea(60,20,680,190);
$myPicture->drawScale(["XMargin"=>1,"DrawSubTicks"=>TRUE,"Mode"=>SCALE_MODE_ADDALL_START0]);

/* Turn on shadow processing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw the stacked area chart */
$pCharts = new pCharts($myPicture);
$pCharts->drawStackedAreaChart(["DrawPlot"=>TRUE,"DrawLine"=>TRUE,"LineSurrounding"=>-20]);

/* Write the chart legend */ 
$myPicture->drawLegend(480,210,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawStackedAreaChart.normalized.png");

?>
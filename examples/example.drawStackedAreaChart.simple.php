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
$myPicture->myData->addPoints([4,1,0,12,8,4,0,12,8],"Frontend #1");
$myPicture->myData->addPoints([3,12,15,8,VOID,VOID,12,15,8],"Frontend #2");
$myPicture->myData->addPoints([4,4,4,4,4,4,4,4,4],"Frontend #3");
$myPicture->myData->setAxisName(0,"Average Usage");
$myPicture->myData->addPoints(["January","February","March","April","May","June","July","August","September"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,  ["StartColor"=>new pColor(240,240,240,100), "EndColor"=>new pColor(180,180,180,100)]);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,["StartColor"=>new pColor(240,240,240,100), "EndColor"=>new pColor(180,180,180,20)]);

/* Set the default font properties */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Draw the scale and the chart */ 
$myPicture->setGraphArea(60,20,680,190);
$myPicture->drawScale(["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"DrawSubTicks"=>TRUE,"Mode"=>SCALE_MODE_ADDALL_START0]);

$pCharts = new pCharts($myPicture);
$pCharts->drawStackedAreaChart(["DrawPlot"=>TRUE,"DrawLine"=>TRUE,"LineSurrounding"=>-20]);

/* Turn on shadow processing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Write the chart legend */ 
$myPicture->drawLegend(480,210,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawStackedAreaChart.simple.png");

?>
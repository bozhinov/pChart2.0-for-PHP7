<?php   
/* CAT:Bar Chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */ 
$myPicture->myData->loadPalette($FileName="pChart/palettes/light.color",$Overwrite=TRUE,$UseCache="Cache/");
$myPicture->myData->addPoints([150,220,300,-250,-420,-200,300,200,100],"Server A");
$myPicture->myData->addPoints([140,0,340,-300,-320,-300,200,100,50],"Server B");
$myPicture->myData->setAxisName(0,"Hits");
$myPicture->myData->addPoints(["January","February","March","April","May","June","July","August","September"],"Months");
$myPicture->myData->setSerieDescription("Months","Month");
$myPicture->myData->setAbscissa("Months");

/* Draw the background */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(240,240,240,100),"EndColor"=>new pColor(180,180,180,100)]);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,["StartColor"=>new pColor(240,240,240,20),"EndColor"=>new pColor(180,180,180,20)]);
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Draw the scale  */
$myPicture->setGraphArea(50,30,680,200);
$myPicture->drawScale(["CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"GridColor"=>new pColor(0,0,0,10)]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw the chart */
(new pCharts($myPicture))->drawBarChart([
	"Gradient"=>TRUE,
	"GradientMode"=>GRADIENT_EFFECT_CAN,
	"DisplayPos"=>LABEL_POS_INSIDE,
	"DisplayValues"=>TRUE,
	"DisplayColor"=>new pColor(255,255,255),
	"DisplayShadow"=>TRUE,
	"Surrounding"=>10
]);

/* Write the chart legend */
$myPicture->drawLegend(580,12,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawBarChart.can.png");

?>
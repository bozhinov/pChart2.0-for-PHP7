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
$myPicture->myData->addPoints([-7,-8,-15,-20,-18,-12,8,-19,9,16,-20,8,10,-10,-14,-20,8,-9,-19],"Probe 3");
$myPicture->myData->addPoints([19,0,-8,8,-8,12,-19,-10,5,12,-20,-8,10,-11,-12,8,-17,-14,0],"Probe 4");
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints([4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22],"Time");
$myPicture->myData->setSerieDescription("Time","Hour of the day");
$myPicture->myData->setAbscissa("Time");
$myPicture->myData->setXAxisUnit("h");

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL, ["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);

/* Set the default font properties */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Draw the scale */
$myPicture->setGraphArea(60,30,650,190);
$myPicture->drawScale(["CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"GridColor"=>new pColor(0,0,0,10),"Mode"=>SCALE_MODE_ADDALL]);

/* Draw some thresholds */
$myPicture->drawThreshold([-40],["WriteCaption"=>TRUE,"Color"=>new pColor(0,0,0),"Ticks"=>4]);
$myPicture->drawThreshold([28],["WriteCaption"=>TRUE,"Color"=>new pColor(0,0,0),"Ticks"=>4]);

/* Draw the chart */
(new pCharts($myPicture))->drawStackedBarChart(["Rounded"=>TRUE,"DisplayValues"=>TRUE,"DisplayType"=>DISPLAY_AUTO,"DisplaySize"=>6,"BorderColor"=>new pColor(255,255,255)]);

/* Write the chart legend */
$myPicture->drawLegend(570,212,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawStackedBarChart.rounded.png");

?>
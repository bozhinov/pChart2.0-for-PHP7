<?php   
/* CAT:Bar Chart */

/* pChart library inclusions */
require_once("bootstrap.php");
require_once("myColors.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(500,500);

/* Populate the pData object */
$myPicture->myData->addPoints([13251,4118,3087,1460,1248,156,26,9,8],"Hits");
$myPicture->myData->setAxisProperties(0, ["Name" => "Hits", "Display" => AXIS_FORMAT_METRIC, "Format" => 1]);
$myPicture->myData->addPoints(["Firefox","Chrome","Internet Explorer","Opera","Safari","Mozilla","SeaMonkey","Camino","Lunascape"],"Browsers");
$myPicture->myData->setSerieDescription("Browsers","Browsers");
$myPicture->myData->setAbscissa("Browsers", ["Name" => "Browsers"]);

$myPicture->drawGradientArea(0,0,500,500,DIRECTION_VERTICAL,  myColors::myGridColor());
$myPicture->drawGradientArea(0,0,500,500,DIRECTION_HORIZONTAL,myColors::myGridColor($Alpha=20));
$myPicture->setFontProperties(["FontName"=>"fonts/Cairo-Regular.ttf","FontSize"=>7]);

/* Draw the chart scale */ 
$myPicture->setGraphArea(100,30,480,480);
$myPicture->drawScale(["CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"GridColor"=>myColors::Black(10),"Pos"=>SCALE_POS_TOPBOTTOM]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>myColors::Black(10)]);

/* Draw the chart */ 
(new pCharts($myPicture))->drawBarChart(["DisplayPos"=>LABEL_POS_INSIDE,"DisplayValues"=>TRUE,"Rounded"=>TRUE,"Surrounding"=>30]);

/* Write the legend */ 
$myPicture->drawLegend(500,215,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawBarChart.vertical.png");


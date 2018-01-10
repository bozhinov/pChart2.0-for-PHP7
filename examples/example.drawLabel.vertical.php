<?php   
/* CAT:Labels */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(500,500);

/* Populate the pData object */
$myPicture->myData->addPoints([13251,4118,3087,1460,1248,156,26,9,8],"Hits");
$myPicture->myData->setAxisName(0,"Hits");
$myPicture->myData->addPoints(["Firefox","Chrome","Internet Explorer","Opera","Safari","Mozilla","SeaMonkey","Camino","Lunascape"],"Browsers");
$myPicture->myData->setSerieDescription("Browsers","Browsers");
$myPicture->myData->setAbscissa("Browsers");

/* Draw the background */
$myPicture->drawGradientArea(0,0,500,500,DIRECTION_VERTICAL,["StartColor"=>new pColor(240,240,240,100), "EndColor"=>new pColor(180,180,180,100)]);
$myPicture->drawGradientArea(0,0,500,500,DIRECTION_HORIZONTAL,["StartColor"=>new pColor(240,240,240,20),"EndColor"=>new pColor(180,180,180,20)]);
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Draw the chart scale */ 
$myPicture->setGraphArea(100,30,480,480);
$myPicture->drawScale(["CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"GridColor"=>new pColor(0,0,0,10),"Pos"=>SCALE_POS_TOPBOTTOM]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Create the per bar palette */
$Palette = [
	0=>new pColor(188,224,46,100),
	1=>new pColor(224,100,46,100),
	2=>new pColor(224,214,46,100),
	3=>new pColor(46,151,224,100),
	4=>new pColor(176,46,224,100),
	5=>new pColor(224,46,117,100),
	6=>new pColor(92,224,46,100),
	7=>new pColor(224,176,46,100)
];

/* Draw the chart */ 
(new pCharts($myPicture))->drawBarChart(["DisplayPos"=>LABEL_POS_INSIDE,"DisplayValues"=>TRUE,"Rounded"=>TRUE,"Surrounding"=>30,"OverrideColors"=>$Palette]);

/* Write the legend */ 
$myPicture->drawLegend(570,215,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Write a label over the chart */
$myPicture->writeLabel(["Hits"],[1,4],["OverrideColors"=>$Palette]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawBarChart.vertical.png");

?>
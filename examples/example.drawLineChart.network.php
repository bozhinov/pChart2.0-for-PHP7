<?php   
/* CAT:Line chart */

/* Set the default timezone */
date_default_timezone_set('Etc/GMT');

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$BaseTs = mktime(0,0,0,12,25,2011);
$LastIn = 0;
$LastOut = 0;
$LastInArr = [];
$LastOutArr = [];
$BaseTsArr = [];

for($i=0; $i<= 1440; $i++)
{
	$LastIn  = abs($LastIn + rand(-1000,+1000));
	$LastOut = abs($LastOut + rand(-1000,+1000));
	$LastInArr[]  = $LastIn;
	$LastOutArr[] = $LastOut;
	$BaseTsArr[]  = $BaseTs+$i*60;
}

$myPicture->myData->addPoints($LastInArr,"Inbound");
$myPicture->myData->addPoints($LastOutArr,"Outbound");
$myPicture->myData->addPoints($BaseTsArr,"TimeStamp");

$myPicture->myData->setAxisName(0,"Bandwidth");
$myPicture->myData->setAxisDisplay(0,AXIS_FORMAT_TRAFFIC);
$myPicture->myData->setSerieDescription("TimeStamp","time");
$myPicture->myData->setAbscissa("TimeStamp");
$myPicture->myData->setXAxisDisplay(AXIS_FORMAT_TIME,"H:00");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw a background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(90,90,90), "Dash"=>TRUE, "DashColor"=>new pColor(120,120,120)]);

/* Overlay with a gradient */ 
$Settings = ["StartColor"=>new pColor(200,200,200,50), "EndColor"=>new pColor(50,50,50,50)];
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,$Settings);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(150,35,"Interface bandwidth usage",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,680,200);

/* Draw the scale */
$myPicture->drawScale([
	"XMargin"=>10,
	"YMargin"=>10,
	"Floating"=>TRUE,
	"GridColor"=>new pColor(200,200,200),
	"RemoveSkippedAxis"=>TRUE,
	"DrawSubTicks"=>FALSE,
	"Mode"=>SCALE_MODE_START0,
	"LabelingMethod"=>LABELING_DIFFERENT
]);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Draw the line chart */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);
(new pCharts($myPicture))->drawLineChart();

/* Write a label over the chart */ 
$myPicture->writeLabel(["Inbound"],[720]);

/* Write the chart legend */
$myPicture->drawLegend(580,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawSplineChart.network.png");

?>
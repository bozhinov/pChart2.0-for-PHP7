<?php   
/* CAT:Line chart */

/* Set the default timezone */
date_default_timezone_set('Etc/GMT');

/* pChart library inclusions */
require_once("bootstrap.php");

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
$Settings = array("R"=>90, "G"=>90, "B"=>90, "Dash"=>1, "DashR"=>120, "DashG"=>120, "DashB"=>120);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings); 

/* Overlay with a gradient */ 
$Settings = array("StartR"=>200, "StartG"=>200, "StartB"=>200, "EndR"=>50, "EndG"=>50, "EndB"=>50, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings); 
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,$Settings); 

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(150,35,"Interface bandwidth usage",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,680,200);

/* Draw the scale */
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"RemoveSkippedAxis"=>TRUE,"DrawSubTicks"=>FALSE,"Mode"=>SCALE_MODE_START0,"LabelingMethod"=>LABELING_DIFFERENT);
$myPicture->drawScale($scaleSettings);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Draw the line chart */
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
(new pCharts($myPicture))->drawLineChart();

/* Write a label over the chart */ 
$myPicture->writeLabel(["Inbound"],[720]);

/* Write the chart legend */
$myPicture->drawLegend(580,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawSplineChart.network.png");

?>
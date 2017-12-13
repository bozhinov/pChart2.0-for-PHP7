<?php

/* pChart library inclusions */
chdir("../../");
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pImageMap;
use pChart\pBubble;

/* Create the pChart object */
/* 							X, Y, TransparentBackground, ImageMapIndex, ImageMapStorageMode, UniqueID, StorageFolder*/
$myPicture = new pImageMap(700,230,FALSE,"ImageMapBubbleChart",IMAGE_MAP_STORAGE_FILE,"BubbleChart","temp");

/* Retrieve the image map */
if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"])){
	$myPicture->dumpImageMap();
}

/* Populate the pData object */  
$myPicture->myData->addPoints([34,55,15,62,38,42],"Probe1");
$myPicture->myData->addPoints([5,30,20,9,15,10],"Probe1Weight");
$myPicture->myData->addPoints([5,10,-5,-1,0,-10],"Probe2");
$myPicture->myData->addPoints([6,10,14,10,14,6],"Probe2Weight");
$myPicture->myData->setSerieDescription("Probe1","This year");
$myPicture->myData->setSerieDescription("Probe2","Last year");
$myPicture->myData->setAxisName(0,"Current stock");
$myPicture->myData->addPoints(array("Apple","Banana","Orange","Lemon","Peach","Strawberry"),"Product");
$myPicture->myData->setAbscissa("Product");
$myPicture->myData->setAbscissaName("Selected Products");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,30,650,190);

/* Draw the scale */
$scaleSettings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Create the Bubble chart object and scale up */
$myPicture->Antialias = TRUE;
$myBubbleChart = new pBubble($myPicture);

/* Scale up for the bubble chart */
$bubbleDataSeries   = array("Probe1","Probe2");
$bubbleWeightSeries = array("Probe1Weight","Probe2Weight");
$myBubbleChart->bubbleScale($bubbleDataSeries,$bubbleWeightSeries);

/* Draw the bubble chart */
$Settings = array("RecordImageMap"=>TRUE,"ForceAlpha"=>50);
$myBubbleChart->drawBubbleChart($bubbleDataSeries,$bubbleWeightSeries,$Settings);

/* Write the chart legend */
$myPicture->drawLegend(570,13,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/Bubble Chart.png");

?>
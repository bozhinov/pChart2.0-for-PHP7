<?php   

/* pChart library inclusions */
chdir("../../");
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;
use pChart\pImageMap\pImageMapFile;

/* Create the pChart object */
/* 							X, Y, TransparentBackground, UniqueID, StorageFolder*/
$myPicture = new pImageMapFile(700,230,FALSE,"PlotChart","temp");

/* Retrieve the image map */
if (isset($_GET["ImageMap"])){
	$myPicture->dumpImageMap();
}

/* Populate the pData object */
$Points_1 = [];
$Points_2 = [];
for($i=0;$i<=20;$i++) {
	$Points_1[] = rand(0,20);
	$Points_2[] = rand(0,20);
}
$myPicture->myData->addPoints($Points_1,"Probe 1"); 
$myPicture->myData->addPoints($Points_2,"Probe 2"); 
$myPicture->myData->setSerieShape("Probe 1",SERIE_SHAPE_FILLEDTRIANGLE);
$myPicture->myData->setSerieWeight("Probe 1",1);
$myPicture->myData->setSerieShape("Probe 2",SERIE_SHAPE_FILLEDSQUARE);
$myPicture->myData->setSerieWeight("Probe 2",2);
$myPicture->myData->setAxisName(0,"Temperatures");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50), "EndColor"=>new pColor(1,138,68,50)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the chart title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);
$myPicture->drawText(150,35,"Average temperature",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Set the default font */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);

/* Define the chart area */
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$myPicture->drawScale(["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridColor"=>new pColor(200,200,200),"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw the line chart */
(new pCharts($myPicture))->drawPlotChart(["RecordImageMap"=>TRUE]);

/* Write the chart legend */
$myPicture->drawLegend(580,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/PlotChart.png");

?>
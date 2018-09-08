<?php   
/* CAT:Mathematical */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Create and populate the pData object */
$myPicture->myData->createFunctionSerie("Serie 1", function($z){return log($z);}, "log(z)", ["AutoDescription"=>TRUE,"MinX"=>-10,"MaxX"=>10,"XStep"=>1,"RecordAbscissa"=>TRUE,"AbscissaSerie"=>"Labels"]);
$myPicture->myData->createFunctionSerie("Serie 2", function($z){return $z*$z*$z;}, "z*z*z", ["AutoDescription"=>TRUE,"MinX"=>-10,"MaxX"=>10,"XStep"=>1]);
$myPicture->myData->createFunctionSerie("Serie 3", function($z){return ($z*15)*$z;}, "(z*15)*z", ["AutoDescription"=>TRUE,"MinX"=>-10,"MaxX"=>10,"XStep"=>1]);
$myPicture->myData->setAxisName(0,"functions");
$myPicture->myData->setAbscissa("Labels");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay some gradients */
$myPicture->drawGradientArea(0,0,700,230, DIRECTION_VERTICAL, ["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(540,0,700,30, DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);
$myPicture->drawRectangle(540,0,699,31,["Color"=>new pColor(0,0,0)]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>20));
$myPicture->drawText(110,35,"Functions computing",["Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$myPicture->drawScale(["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridColor"=>new pColor(200,200,200),"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Turn on shadows */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw the 0 threshold */
$myPicture->drawThreshold([0],["Color"=>new pColor(255,0,0,70),"Ticks"=>1]);
$myPicture->drawXThreshold([10],["Color"=>new pColor(255,0,0,70),"Ticks"=>1]);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw a zone chart */
$pCharts->drawZoneChart("Serie 2","Serie 3",["AreaColor"=>new pColor(200,150,150,30)]);

/* Draw the line chart */
$pCharts->drawLineChart();
$pCharts->drawPlotChart(["PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-60,"BorderColor"=>new pColor(50,50,50,80)]);

/* Write the chart legend */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11,"Color"=>new pColor(255,255,255)));
$myPicture->drawLegend(560,15,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.createFunctionSerie.png");

?>
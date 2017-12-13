<?php   
/* CAT:Mathematical */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Create and populate the pData object */
$myPicture->myData->createFunctionSerie("Serie 1","log(z)",array("AutoDescription"=>TRUE,"MinX"=>-10,"MaxX"=>10,"XStep"=>1,"RecordAbscissa"=>TRUE,"AbscissaSerie"=>"Labels"));
$myPicture->myData->createFunctionSerie("Serie 2","z*z*z",array("AutoDescription"=>TRUE,"MinX"=>-10,"MaxX"=>10,"XStep"=>1));
$myPicture->myData->createFunctionSerie("Serie 3","(z*15)*z",array("AutoDescription"=>TRUE,"MinX"=>-10,"MaxX"=>10,"XStep"=>1));
$myPicture->myData->setAxisName(0,"functions");
$myPicture->myData->setAbscissa("Labels");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings); 

/* Overlay some gradients */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings); 
$myPicture->drawGradientArea(540,0,700,30,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);
$myPicture->drawRectangle(540,0,699,31,["R"=>0,"G"=>0,"B"=>0]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>20));
$myPicture->drawText(110,35,"Functions computing",array("Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Turn on shadows */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);

/* Draw the 0 threshold */
$myPicture->drawThreshold(0,["Alpha"=>70,"Ticks"=>1]);
$myPicture->drawXThreshold(10,["Alpha"=>70,"Ticks"=>1]);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw a zone chart */
$pCharts->drawZoneChart("Serie 2","Serie 3",["AreaR"=>200,"AreaAlpha"=>30]);

/* Draw the line chart */
$pCharts->drawLineChart();
$pCharts->drawPlotChart(["PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-60,"BorderAlpha"=>80]);

/* Write the chart legend */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11,"R"=>255,"G"=>255,"B"=>255));
$myPicture->drawLegend(560,15,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.createFunctionSerie.png");

?>
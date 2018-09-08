<?php
/* CAT:Combo */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

$myPicture->myData->addPoints([30,24,32],"This year");
$myPicture->myData->addPoints([28,20,27],"Last year");
$myPicture->myData->setSerieTicks("Last year",4);
$myPicture->myData->addPoints(["Year","Month","Day"],"Labels");
$myPicture->myData->setAbscissa("Labels");

/* Turn on anti-aliasing */
$myPicture->Antialias = FALSE;

/* Create a solid background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

/* Do a gradient overlay */
$myPicture->drawGradientArea(0,0,700,230, DIRECTION_VERTICAL, ["StartColor"=>new pColor(194,217,91,50),"EndColor"=>new pColor(44,107,58,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL,  ["StartColor"=>new pColor(0,0,0,100),"EndColor"=>new pColor(50,50,50,100)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"Chart title",["Color"=>new pColor(255,255,255)]);

/* Draw the scale */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$myPicture->setGraphArea(50,60,670,190);
$myPicture->drawFilledRectangle(50,60,670,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["CycleBackground"=>TRUE]);

/* Graph title */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);
$myPicture->drawText(50,52,"Chart subtitle",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMLEFT]);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw the bar chart chart */
$pCharts->myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$pCharts->myPicture->myData->setSerieDrawable("Last year",FALSE);
$pCharts->drawBarChart();

/* Turn on anti-aliasing */
$myPicture->Antialias = TRUE;

/* Draw the line and plot chart */
$pCharts->myPicture->myData->setSerieDrawable("Last year",TRUE);
$pCharts->myPicture->myData->setSerieDrawable("This year",FALSE);
$pCharts->myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,10)]);
$pCharts->drawSplineChart();

$myPicture->setShadow(FALSE);
$pCharts->drawPlotChart(["PlotSize"=>3,"PlotBorder"=>TRUE,"BorderSize"=>3,"BorderColor"=>new pColor(50,50,50,20)]);

/* Make sure all series are drawable before writing the scale */
$myPicture->myData->setAllDrawable(); # was drawAll

/* Write the legend */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);
$myPicture->drawLegend(580,35,["Style"=>LEGEND_ROUND,"Color"=>new pColor(200,200,200,20),"BorderColor"=>new pColor(255,255,255,20),"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.combo.png");

?>
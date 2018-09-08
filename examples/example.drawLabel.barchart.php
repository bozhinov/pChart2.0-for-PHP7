<?php   
/* CAT:Labels */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->loadPalette("pChart/palettes/autumn.color",TRUE);
$myPicture->myData->addPoints([4,12,15,8,5,-5],"Probe 1");
$myPicture->myData->addPoints([7,2,4,14,8,3],"Probe 2");
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->setAxisUnit(0,"°C");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"drawLabel() - Write labels over your charts",["Color"=>new pColor(255,255,255)]);

/* Write the chart title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);
$myPicture->drawText(155,55,"Average temperature",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Draw the scale and the 1st chart */
$myPicture->setGraphArea(60,60,670,190);
$myPicture->drawFilledRectangle(60,60,670,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$myPicture->drawScale(["DrawSubTicks"=>TRUE]);
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw the bar chart */
(new pCharts($myPicture))->drawBarChart();
$myPicture->setShadow(FALSE);

/* Write the chart legend */
$myPicture->drawLegend(600,210,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);

/* Write a label over the chart */
$myPicture->writeLabel(["Probe 1"],[0]);
$myPicture->writeLabel(["Probe 1"],[5],["TitleMode"=>LABEL_TITLE_BACKGROUND,"DrawSerieColor"=>FALSE,"TitleColor"=>new pColor(255,255,255)]);
$myPicture->writeLabel(["Probe 1","Probe 2"],[4],["OverrideTitle"=>"Multiple series","DrawVerticalLine"=>TRUE,"TitleMode"=>LABEL_TITLE_BACKGROUND,"TitleColor"=>new pColor(255,255,255)]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawLabel.png");

?>
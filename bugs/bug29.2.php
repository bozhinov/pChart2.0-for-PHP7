<?php

/* pChart library inclusions */
require_once("examples\bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pPie;

/* Create the pChart object */
$myPicture = new pDraw(500,400);

/* Populate the pData object */
$myPicture->myData->addPoints([50, 10, 35, 5], "Serie1");

/* Define the abscissa serie */
$myPicture->myData->addPoints(["50€ ", "10€", "35€", "5€"],"Labels");
$myPicture->myData->setAbscissa("Labels");

/* Draw a solid background */
$myPicture->drawFilledRectangle(0,0,500,400,["Color"=>new pColor(30,40,30,20)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,500,400, DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);

/* Set the default font properties */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>12,"Color"=>new pColor(80)]);

/* Create the pPie object */
$PieChart = new pPie($myPicture);

/* Draw an AA pie chart */
$PieChart->draw3DRing(215,200,["Radius"=>53, "DrawLabels"=>TRUE,"WriteValues"=>PIE_VALUE_NATURAL,"DataGapAngle"=>8,"DataGapRadius"=>4,"Border"=>TRUE, "BorderColor"=>new pColor(40)]);

/* Write the legend box */
$PieChart->drawPieLegend(20,40,["FontSize"=>12, "BoxSize"=>16,"Style"=>LEGEND_BOX,"Mode"=>LEGEND_VERTICAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/bug29.2.png");

?>
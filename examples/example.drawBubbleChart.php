<?php
/* CAT:Bubble chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pBubble
};

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->loadPalette("pChart/palettes/summer.color",TRUE);
$myPicture->myData->addPoints([34,55,15,62,38,42],"Probe1");
$myPicture->myData->addPoints([5,10,8,9,15,10],"Probe1Weight");
$myPicture->myData->addPoints([5,10,-5,-1,0,-10],"Probe2");
$myPicture->myData->addPoints([6,10,14,10,14,6],"Probe2Weight");
$myPicture->myData->setSerieDescription("Probe1","This year");
$myPicture->myData->setSerieDescription("Probe2","Last year");
$myPicture->myData->setAxisName(0,"Current stock");
$myPicture->myData->addPoints(["Apple","Banana","Orange","Lemon","Peach","Strawberry"],"Product");
$myPicture->myData->setAbscissa("Product");

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawBubbleChart() - draw a linear bubble chart",["Color"=>new pColor(255,255,255)]);

/* Write the title */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(40,55,"Current Stock / Needs chart",array("FontSize"=>14,"Align"=>TEXT_ALIGN_BOTTOMLEFT));

/* Change the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Create the Bubble chart object and scale up */
$myBubbleChart = new pBubble($myPicture);

/* Scale up for the bubble chart */
$DataSeries   = ["Probe1","Probe2"];
$WeightSeries = ["Probe1Weight","Probe2Weight"];
$myBubbleChart->bubbleScale($DataSeries,$WeightSeries);

/* Draw the 1st chart */
$myPicture->setGraphArea(40,60,430,190);
$myPicture->drawFilledRectangle(40,60,430,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-50]);
$myPicture->drawScale(["DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,30)]);
$myBubbleChart->drawBubbleChart($DataSeries,$WeightSeries);

/* Draw the 2nd scale */
$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(500,60,670,190);
$myPicture->drawFilledRectangle(500,60,670,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["Pos"=>SCALE_POS_TOPBOTTOM,"DrawSubTicks"=>TRUE]);

/* Draw the 2nd stock chart */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,30)]);
$myBubbleChart->drawbubbleChart($DataSeries,$WeightSeries,["DrawBorder"=>TRUE,"Surrounding"=>60,"BorderColor"=>new pColor(255,255,255,50)]);

/* Write the chart legend */
$myPicture->drawLegend(550,215,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawBubbleChart.png");

?>
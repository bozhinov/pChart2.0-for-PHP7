<?php   
/* CAT:Mathematical */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */  
$myPicture->myData->addPoints([-4,VOID,VOID,12,8,3,4,5,0,4,6,10],"Probe 1");
$myPicture->myData->addPoints([3,12,15,8,5,-5,5,-5,-3,4,5,10],"Probe 2");
$myPicture->myData->addPoints([2,7,5,18,19,22,10,2,6,10,5,6],"Probe 3");
$myPicture->myData->setSerieTicks("Probe 2",4);
$myPicture->myData->setSerieWeight("Probe 3",2);
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");
$myPicture->myData->setAbscissaName("Months");

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawDerivative() - draw the series slope",["Color"=>new pColor(255,255,255)]);

/* Set the default font */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Draw the scale and the 1st chart */
$myPicture->setGraphArea(60,40,680,150);
$myPicture->drawFilledRectangle(60,40,680,150,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["DrawSubTicks"=>TRUE]);
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

$myCharts = new pCharts($myPicture);
$myCharts->drawLineChart(["DisplayValues"=>TRUE,"DisplayType"=>DISPLAY_AUTO]);

/* Draw the series derivative graph */
$myCharts->drawDerivative(["ShadedSlopeBox"=>TRUE,"CaptionLine"=>TRUE]);

/* Write the chart legend */
$myPicture->setFontProperties(["Color"=>new pColor(255,255,255)]);
$myPicture->drawLegend(560,8,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawDerivative.png");

?>
<?php   
/* CAT:Stacked chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([1,-2,-1,2,1,0],"Probe 1");
$myPicture->myData->addPoints([1,-2,-3,2,1,8],"Probe 2");
$myPicture->myData->addPoints([2,4,2,0,4,2],"Probe 3");
$myPicture->myData->setSerieTicks("Probe 2",4);
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL, ["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawStackedAreaChart() - draw a stacked area chart",["Color"=>new pColor(255,255,255)]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(250,55,"Average temperature",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Draw the scale and the 1st chart */
$myPicture->setGraphArea(60,60,450,190);
$myPicture->drawFilledRectangle(60,60,450,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["DrawSubTicks"=>TRUE,"Mode"=>SCALE_MODE_ADDALL]);
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
$myPicture->setShadow(FALSE);

$pCharts = new pCharts($myPicture);
$pCharts->drawStackedAreaChart(["DisplayValues"=>TRUE,"DisplayType"=>DISPLAY_AUTO,"DrawPlot"=>TRUE,"DrawLine"=>TRUE,"LineSurrounding"=>-250]);

/* Draw one static threshold */ 
$myPicture->drawThreshold([0],["Color"=>new pColor(255,0,0,70),"Ticks"=>1,"NoMargin"=>TRUE]);

/* Draw the scale and the 2nd chart */
$myPicture->setGraphArea(500,60,670,190);
$myPicture->drawFilledRectangle(500,60,670,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["Pos"=>SCALE_POS_TOPBOTTOM,"Mode"=>SCALE_MODE_ADDALL,"DrawSubTicks"=>TRUE]);
$myPicture->setShadow(FALSE);

$pCharts->drawStackedAreaChart(["Surrounding"=>10]);

/* Draw one static threshold */ 
$myPicture->drawThreshold([0],["Color"=>new pColor(255,0,0,70),"Ticks"=>1,"NoMargin"=>TRUE]);

/* Write the chart legend */
$myPicture->drawLegend(510,205,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawStackedAreaChart.png");

?>
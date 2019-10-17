<?php   
/* CAT:Spline chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$Points_1 = [];
for($i=0;$i<=720;$i=$i+20)
{ 
	$Points_1[] = cos(deg2rad($i))*100;
}
$myPicture->myData->addPoints($Points_1,"Probe 1"); 
$myPicture->myData->setAxisName(0,"Strength");

$myPicture->drawGradientArea(0,0,700,230,  DIRECTION_VERTICAL, ["StartColor"=>new pColor(47), "EndColor"=>new pColor(17)]);
$myPicture->drawGradientArea(0,230,700,230,DIRECTION_VERTICAL, ["StartColor"=>new pColor(47), "EndColor"=>new pColor(27)]);
$myPicture->drawLine(0,209,700,209,["Color"=>new pColor(0)]);
$myPicture->drawLine(0,210,700,210,["Color"=>new pColor(70)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,700,230,["Color"=>new pColor(204)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"fonts/Cairo-Regular.ttf","FontSize"=>7]);
$myPicture->drawText(340,12,"Cyclic magnetic field strength",["Color"=>new pColor(255),"Align"=>TEXT_ALIGN_MIDDLEMIDDLE]);

/* Define the chart area */
$myPicture->setGraphArea(48,17,680,190);

/* Draw a rectangle */
$myPicture->drawFilledRectangle(53,22,675,185,["Color"=>new pColor(0),"Dash"=>TRUE,"DashColor"=>new pColor(0,51,51),"BorderColor"=>new pColor(0)]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);

/* Draw the scale */
$myPicture->setFontProperties(["Color"=>new pColor(255)]);
$myPicture->drawScale([
	"XMargin"=>5,
	"YMargin"=>5,
	"Floating"=>TRUE,
	"DrawSubTicks"=>TRUE,
	"GridColor"=>new pColor(255,255,255,30),
	"AxisColor"=>new pColor(255),
	"CycleBackground"=>TRUE
]);

/* Define the visual thresholds */
$Threshold = [
	array("Min"=>-100,"Max"=>-35,"Color"=>new pColor(117,140,240,40)),
	array("Min"=>-35,"Max"=>35,"Color"=>new pColor(240,232,20,60)),
	array("Min"=>35,"Max"=>100,"Color"=>new pColor(240,121,20,80))
]; 
/* Draw the spline chart */
(new pCharts($myPicture))->drawFilledSplineChart(["Threshold"=>$Threshold]);

/* Write the chart boundaries */
$myPicture->writeBounds(BOUND_BOTH,["MaxDisplayColor"=>new pColor(237,23,48),"MinDisplayColor"=>new pColor(23,144,237)]);

/* Write the 0 line */
$myPicture->drawThreshold([0],["WriteCaption"=>TRUE]);

/* Write the chart legend */
$myPicture->setFontProperties(["Color"=>new pColor(255)]);
$myPicture->drawLegend(620,217,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Write the 1st data series statistics */
$Settings = ["Color"=>new pColor(188,224,46),"Align"=>TEXT_ALIGN_BOTTOMLEFT];
$myPicture->drawText(10,222,"Max : ".ceil($myPicture->myData->getMax("Probe 1")),$Settings);
$myPicture->drawText(60,222,"Min : ".ceil($myPicture->myData->getMin("Probe 1")),$Settings);
$myPicture->drawText(110,222,"Avg : ".ceil($myPicture->myData->getSerieAverage("Probe 1")),$Settings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawFilledSplineChart.Threshold.png");

?>
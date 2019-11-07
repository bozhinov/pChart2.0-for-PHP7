<?php   
/* CAT:Scatter chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pScatter;

/* Create the pChart object */
$myPicture = new pDraw(400,400);

/* Create the X axis and the binded series */
$Points_1 = [];
$Points_2 = [];
for($i=0;$i<=360;$i=$i+10) 
{
	$Points_1[] = cos(deg2rad($i))*20;
	$Points_2[] = sin(deg2rad(30-$i))*20;
}
$myPicture->myData->addPoints($Points_1,"Probe 1");
$myPicture->myData->addPoints($Points_2,"Probe 2");

$myPicture->myData->setAxisProperties(0, ["Name" => "X-Index", "Identity" => AXIS_X, "Position" => AXIS_POSITION_BOTTOM]);

/* Create the Y axis and the binded series */
$myPicture->myData->setSerieOnAxis("Probe 2",1);
$myPicture->myData->setAxisProperties(1, ["Name" => "Y-Index", "Identity" => AXIS_Y, "Position" => AXIS_POSITION_RIGHT]);

/* Create the 1st scatter chart binding */
$myPicture->myData->setScatterSerie("Probe 1","Probe 2",0);
$myPicture->myData->setScatterSerieProperties(0, [
	"Description" => "Trigonometric function",
	"Color" => new pColor(180,0,0),
	"Ticks" => 4,
	"Picture" => "examples/resources/chart_line.png"
]);

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,400,400,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,400,400,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,400,20, DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"fonts/PressStart2P-Regular.ttf","FontSize"=>6]);
$myPicture->drawText(10,15,"drawScatterLineChart() - Draw a scatter line chart",["Color"=>new pColor(255)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,399,399,["Color"=>new pColor(0)]);

/* Set the default font */
$myPicture->setFontProperties(["FontName"=>"fonts/Cairo-Regular.ttf","FontSize"=>6]);

/* Set the graph area */
$myPicture->setGraphArea(50,50,350,350);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture);

/* Draw the scale */
$myScatter->drawScatterScale();

/* Turn on shadow computing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw a scatter plot chart */
$myScatter->drawScatterLineChart();

/* Draw the legend */
$myScatter->drawScatterLegend(270,375,["Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawScatterLineChart.trigo.png");


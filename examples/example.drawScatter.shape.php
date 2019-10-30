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
$Points_3 = [];
for($i=0;$i<=20;$i++)
{
	$Points_1[] = rand(1,20)+$i;
	$Points_2[] = rand(1,20)+$i;
	$Points_3[] = rand(0,20)+$i;
}
$myPicture->myData->addPoints($Points_1,"Probe 1");
$myPicture->myData->addPoints($Points_2,"Probe 2");
$myPicture->myData->addPoints($Points_3,"Probe 3");

/* Create the X axis and the binded series */
$myPicture->myData->setAxisProperties(0, ["Name" => "X-Index", "Identity" => AXIS_X, "Position" => AXIS_POSITION_TOP]);

/* Create the Y axis and the binded series */
$myPicture->myData->setSerieOnAxis("Probe 3",1);
$myPicture->myData->setAxisProperties(1, ["Name" => "Y-Index", "Identity" => AXIS_Y, "Position" => AXIS_POSITION_LEFT]);

/* Create the 1st scatter chart binding */
$myPicture->myData->setScatterSerie("Probe 1","Probe 3",0);
$myPicture->myData->setScatterSerieProperties(0, ["Description" => "This year", "Color" => new pColor(0), "Shape" => SERIE_SHAPE_TRIANGLE]);

/* Create the 2nd scatter chart binding */
$myPicture->myData->setScatterSerie("Probe 2","Probe 3",1);
$myPicture->myData->setScatterSerieProperties(1, ["Description" => "Last Year", "Shape" => SERIE_SHAPE_FILLEDTRIANGLE]);

/* Turn off Anti-aliasing */
$myPicture->setAntialias(FALSE);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,399,399,["Color"=>new pColor(0)]);

/* Set the default font */
$myPicture->setFontProperties(["FontName"=>"fonts/Cairo-Regular.ttf","FontSize"=>7]);

/* Set the graph area */
$myPicture->setGraphArea(40,40,370,370);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture);

/* Draw the scale */
$myScatter->drawScatterScale(["XMargin"=>15,"YMargin"=>15,"Floating"=>TRUE,"GridColor"=>new pColor(200),"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);

/* Draw the legend */
$myScatter->drawScatterLegend(280,380,["Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER]);

/* Draw a scatter plot chart */
$myPicture->setAntialias(TRUE);
$myScatter->drawScatterPlotChart();

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.example.drawScatterShape.png");

?>
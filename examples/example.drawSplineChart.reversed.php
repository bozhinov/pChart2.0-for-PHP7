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
$myPicture->myData->addPoints([4,VOID,VOID,12,8,3],"Probe 1");
$myPicture->myData->addPoints([3,12,15,8,5,5],"Probe 2");
$myPicture->myData->addPoints([2,7,5,18,19,22],"Probe 3");
$myPicture->myData->setPalette("Probe 1",new pColor(220,60,20));
$myPicture->myData->setSerieProperties("Probe 2", ["Ticks" => 4]);
$myPicture->myData->setSerieProperties("Probe 3", ["Weight" => 2]);
$myPicture->myData->setAxisProperties(0, [
	"Name" => "Temperatures",
	"Display" => AXIS_FORMAT_CUSTOM,
	"Format" => function($Value){ # NegateValues
		($Value == VOID) ? VOID : -$Value;
	}
]);

$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels", ["Position" => AXIS_POSITION_TOP]);

/* Reverse the Y axis trick */
$myPicture->myData->NegateValues(["Probe 1","Probe 2","Probe 3"]);

/* Turn off Anti-aliasing */
$myPicture->setAntialias(FALSE);

/* Draw a background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(190,213,107), "Dash"=>TRUE, "DashColor"=>new pColor(210,223,127)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0)]);

/* Write the chart title */ 
$myPicture->setFontProperties(["FontName"=>"fonts/Cairo-Regular.ttf","FontSize"=>11]);
$myPicture->drawText(150,35,"Average temperature",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Set the default font */
$myPicture->setFontProperties(["FontSize"=>7]);

/* Define the chart area */
$myPicture->setGraphArea(60,60,650,220);

/* Draw the scale */
$myPicture->drawScale(["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridColor"=>new pColor(200),"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);

/* Turn on Anti-aliasing */
$myPicture->setAntialias(TRUE);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw the line chart */
$pCharts->drawSplineChart();
$pCharts->drawPlotChart(["PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-60,"BorderColor"=>new pColor(0,0,0,80)]);

/* Write the chart legend */
$myPicture->drawLegend(540,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawSplineChart.simple.png");

?>
<?php   
/* CAT:Area Chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */ 
for($i=0;$i<=30;$i++) 
{ 
	$myPicture->myData->addPoints([rand(1,15)],"Probe 1");
}
/* Momchil: setSerieTicks only sets it if Serie is defined. Probe 2 isn't
$myPicture->myData->setSerieTicks("Probe 2",4);
*/
$myPicture->myData->setAxisName(0,"Temperatures");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Add a border to the picture */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(240,240,240,100), "EndColor"=>new pColor(180,180,180,100)]);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,["StartColor"=>new pColor(240,240,240,20), "EndColor"=>new pColor(180,180,180,20)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(150,35,"Average temperature",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$myPicture->drawScale(["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridColor"=>new pColor(200,200,200,100),"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);

/* Write the chart legend */
$myPicture->drawLegend(640,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Enable shadow computing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw the area chart */
$Threshold = [
	array("Min"=>0,"Max"=>5,"Color"=>new pColor(187,220,0,100)),
	array("Min"=>5,"Max"=>10,"Color"=>new pColor(240,132,20,100)),
	array("Min"=>10,"Max"=>20,"Color"=>new pColor(240,91,20,100))
];

$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

$pCharts->drawAreaChart(["Threshold"=>$Threshold]);

/* Draw a line chart over */
$pCharts->drawLineChart(["UseForcedColor"=>TRUE,"ForceColor"=>new pColor(0,0,0,100)]);

/* Draw a plot chart over */
$pCharts->drawPlotChart(["PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-255,"BorderColor"=>new pColor(50,50,50,80)]);

/* Write the thresholds */
$myPicture->drawThreshold([5],["WriteCaption"=>TRUE,"Caption"=>"Warn Zone","Ticks"=>2,"Color"=>new pColor(0,0,255,70)]);
$myPicture->drawThreshold([10],["WriteCaption"=>TRUE,"Caption"=>"Error Zone","Ticks"=>2,"Color"=>new pColor(0,0,255,70)]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawAreaChart.threshold.png");

?>
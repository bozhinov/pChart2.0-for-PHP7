<?php   
/* CAT:Area Chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */ 
for($i=0;$i<=30;$i++) 
{ 
	$myPicture->myData->addPoints(rand(1,15),"Probe 1"); 
}
$myPicture->myData->setSerieTicks("Probe 2",4);
$myPicture->myData->setAxisName(0,"Temperatures");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Add a border to the picture */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20));

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(150,35,"Average temperature",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"GridAlpha"=>100,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Write the chart legend */
$myPicture->drawLegend(640,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Enable shadow computing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);

/* Draw the area chart */
$Threshold = [
	array("Min"=>0,"Max"=>5,"R"=>187,"G"=>220,"B"=>0,"Alpha"=>100),
	array("Min"=>5,"Max"=>10,"R"=>240,"G"=>132,"B"=>20,"Alpha"=>100),
	array("Min"=>10,"Max"=>20,"R"=>240,"G"=>91,"B"=>20,"Alpha"=>100)
];

$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20]);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

$pCharts->drawAreaChart(["Threshold"=>$Threshold]);

/* Draw a line chart over */
$pCharts->drawLineChart(["ForceColor"=>TRUE,"ForceR"=>0,"ForceG"=>0,"ForceB"=>0]);

/* Draw a plot chart over */
$pCharts->drawPlotChart(["PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-255,"BorderAlpha"=>80]);

/* Write the thresholds */
$myPicture->drawThreshold(5,["WriteCaption"=>TRUE,"Caption"=>"Warn Zone","Alpha"=>70,"Ticks"=>2,"R"=>0,"G"=>0,"B"=>255]);
$myPicture->drawThreshold(10,["WriteCaption"=>TRUE,"Caption"=>"Error Zone","Alpha"=>70,"Ticks"=>2,"R"=>0,"G"=>0,"B"=>255]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawAreaChart.threshold.png");

?>
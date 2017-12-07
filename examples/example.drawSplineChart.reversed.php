<?php   
/* CAT:Spline chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints(array(4,VOID,VOID,12,8,3),"Probe 1");
$myPicture->myData->addPoints(array(3,12,15,8,5,5),"Probe 2");
$myPicture->myData->addPoints(array(2,7,5,18,19,22),"Probe 3");
$myPicture->myData->setPalette("Probe 1",array("R"=>220,"G"=>60,"B"=>20));
$myPicture->myData->setSerieTicks("Probe 2",4);
$myPicture->myData->setSerieWeight("Probe 3",2);
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints(array("Jan","Feb","Mar","Apr","May","Jun"),"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

/* Reverse the Y axis trick */
$myPicture->myData->->setAbsicssaPosition(AXIS_POSITION_TOP);
$myPicture->myData->->NegateValues(array("Probe 1","Probe 2","Probe 3"));
$myPicture->myData->->setAxisDisplay(0,AXIS_FORMAT_CUSTOM,"NegateValues"); 

function NegateValues($Value) { 
	return ($Value == VOID) ? VOID : -$Value;
}

/* Turn of Antialiasing */
$myPicture->Antialias = FALSE;

/* Draw a background */
$Settings = array("R"=>190, "G"=>213, "B"=>107, "Dash"=>1, "DashR"=>210, "DashG"=>223, "DashB"=>127); 
$myPicture->drawFilledRectangle(0,0,700,230,$Settings); 

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(150,35,"Average temperature",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,60,650,220);

/* Draw the scale */
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Turn on Antialiasing */
$myPicture->Antialias = TRUE;

/* Create the pMist object */
$pCharts = new pCharts($myPicture);

/* Draw the line chart */
$pCharts->drawSplineChart();
$pCharts->drawPlotChart(["PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-60,"BorderAlpha"=>80]);

/* Write the chart legend */
$myPicture->drawLegend(540,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawSplineChart.simple.png");

?>
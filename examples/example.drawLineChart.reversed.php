<?php   
/* CAT:Line chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([3,12,15,8,5,5],"Probe 1");
$myPicture->myData->addPoints([8,7,5,18,19,22],"Probe 2");
$myPicture->myData->setSerieWeight("Probe 1",2);
$myPicture->myData->setSerieTicks("Probe 2",4);
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

/* Reverse the Y axis trick */
$myPicture->myData->setAbsicssaPosition(AXIS_POSITION_TOP);
$myPicture->myData->NegateValues(array("Probe 1","Probe 2","Probe 3"));
$myPicture->myData->setAxisDisplay(0,AXIS_FORMAT_CUSTOM,"NegateValues"); 

function NegateValues($Value) {
	return ($Value == VOID) ? VOID : -$Value;
}

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,["StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>8,"R"=>255,"G"=>255,"B"=>255));
$myPicture->drawText(10,16,"Average recorded temperature",array("FontSize"=>11,"Align"=>TEXT_ALIGN_BOTTOMLEFT));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6,"R"=>0,"G"=>0,"B"=>0));

/* Define the chart area */
$myPicture->setGraphArea(60,50,650,220);

/* Draw the scale */
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Enable shadow computing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw the line chart */
$pCharts->drawLineChart();
$pCharts->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80));

/* Write the chart legend */
$myPicture->drawLegend(590,9,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"FontR"=>255,"FontG"=>255,"FontB"=>255));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawLineChart.reversed.png");

?>
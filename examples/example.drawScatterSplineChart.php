<?php   
/* CAT:Scatter chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pScatter;

/* Create the pChart object */
$myPicture = new pDraw(400,400);

/* Create the X axis and the binded series */
for ($i=0;$i<=360;$i=$i+90) { 
	$myPicture->myData->addPoints(rand(1,30),"Probe 1"); 
}
for ($i=0;$i<=360;$i=$i+90) { 
	$myPicture->myData->addPoints(rand(1,30),"Probe 2"); 
}

$myPicture->myData->setAxisName(0,"Index");
$myPicture->myData->setAxisXY(0,AXIS_X);
$myPicture->myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

/* Create the Y axis and the binded series */
for ($i=0;$i<=360;$i=$i+90) { 
	$myPicture->myData->addPoints($i,"Probe 3");
}

$myPicture->myData->setSerieOnAxis("Probe 3",1);
$myPicture->myData->setAxisName(1,"Degree");
$myPicture->myData->setAxisXY(1,AXIS_Y);
$myPicture->myData->setAxisUnit(1,"°");
$myPicture->myData->setAxisPosition(1,AXIS_POSITION_RIGHT);

/* Create the 1st scatter chart binding */
$myPicture->myData->setScatterSerie("Probe 1","Probe 3",0);
$myPicture->myData->setScatterSerieDescription(0,"This year");
$myPicture->myData->setScatterSerieTicks(0,4);
$myPicture->myData->setScatterSerieColor(0,array("R"=>0,"G"=>0,"B"=>0));

/* Create the 2nd scatter chart binding */
$myPicture->myData->setScatterSerie("Probe 2","Probe 3",1);
$myPicture->myData->setScatterSerieDescription(1,"Last Year");

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,400,400,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,400,400,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,400,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawScatterSplineChart() - Draw a scatter spline chart",array("R"=>255,"G"=>255,"B"=>255));

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,399,399,array("R"=>0,"G"=>0,"B"=>0));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Set the graph area */
$myPicture->setGraphArea(50,50,350,350);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture);

/* Draw the scale */
$myScatter->drawScatterScale();

/* Turn on shadow computing */
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

/* Draw a scatter plot chart */
$myScatter->drawScatterSplineChart();
$myScatter->drawScatterPlotChart();

/* Draw the legend */
$myScatter->drawScatterLegend(280,380,array("Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawScatterSplineChart.png");

?>
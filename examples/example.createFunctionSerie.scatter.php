<?php   
/* CAT:Mathematical */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pDraw,
	pScatter
};

/* Create the pChart object */
$myPicture = new pDraw(400,400);

/* Create the X axis and the binded series */
$myPicture->myData->createFunctionSerie("X","1/z",["MinX"=>-10,"MaxX"=>10,"XStep"=>1]);
$myPicture->myData->setAxisName(0,"x = 1/z");
$myPicture->myData->setAxisXY(0,AXIS_X);
$myPicture->myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

/* Create the Y axis */
$myPicture->myData->createFunctionSerie("Y","z",["MinX"=>-10,"MaxX"=>10,"XStep"=>1]);
$myPicture->myData->setSerieOnAxis("Y",1);
$myPicture->myData->setAxisName(1,"y = z");
$myPicture->myData->setAxisXY(1,AXIS_Y);
$myPicture->myData->setAxisPosition(1,AXIS_POSITION_RIGHT);

/* Create the Y axis */
$myPicture->myData->createFunctionSerie("Y2","z*z*z",["MinX"=>-10,"MaxX"=>10,"XStep"=>1]);
$myPicture->myData->setSerieOnAxis("Y2",2);
$myPicture->myData->setAxisName(2,"y = z*z*z");
$myPicture->myData->setAxisXY(2,AXIS_Y);
$myPicture->myData->setAxisPosition(2,AXIS_POSITION_LEFT);

/* Create the 1st scatter chart binding */
$myPicture->myData->setScatterSerie("X","Y",0);
$myPicture->myData->setScatterSerieDescription(0,"Pass A");
$myPicture->myData->setScatterSerieTicks(0,4);
$myPicture->myData->setScatterSerieColor(0,["R"=>0,"G"=>0,"B"=>0]);

/* Create the 2nd scatter chart binding */
$myPicture->myData->setScatterSerie("X","Y2",1);
$myPicture->myData->setScatterSerieDescription(1,"Pass B");
$myPicture->myData->setScatterSerieTicks(1,4);
$myPicture->myData->setScatterSerieColor(1,["R"=>120,"G"=>0,"B"=>255]);

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,400,400,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,400,400,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,400,20,DIRECTION_VERTICAL,["StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"createFunctionSerie() - Functions computing",["R"=>255,"G"=>255,"B"=>255]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,399,399,["R"=>0,"G"=>0,"B"=>0]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Set the graph area */
$myPicture->setGraphArea(50,50,350,350);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture);

/* Draw the scale */
$myScatter->drawScatterScale(["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE]);

/* Turn on shadow computing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);

/* Draw the 0/0 lines */
$myScatter->drawScatterThreshold(0,["AxisID"=>0,"R"=>0,"G"=>0,"B"=>0,"Ticks"=>10]);
$myScatter->drawScatterThreshold(0,["AxisID"=>1,"R"=>0,"G"=>0,"B"=>0,"Ticks"=>10]);

/* Draw a threshold area */
$myScatter->drawScatterThresholdArea(-0.1,0.1,["AreaName"=>"Error zone"]); /* ["AreaName"=>NULL] */

/* Draw a scatter plot chart */
$myScatter->drawScatterLineChart();
$myScatter->drawScatterPlotChart();

/* Draw the legend */
$myScatter->drawScatterLegend(300,380,["Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.createFunctionSerie.scatter.png");

?>
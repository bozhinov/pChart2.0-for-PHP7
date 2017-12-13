<?php   
/* CAT:Scatter chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pScatter;

/* Create the pChart object */
$myPicture = new pDraw(400,400);

/* Create the X axis and the binded series */
for ($i=0;$i<=10;$i=$i+1) { 
	$myPicture->myData->addPoints(rand(1,20),"Probe 1"); 
}
for ($i=0;$i<=10;$i=$i+1) {
	$myPicture->myData->addPoints(rand(1,20),"Probe 2"); 
}
$myPicture->myData->setAxisName(0,"X-Index");
$myPicture->myData->setAxisXY(0,AXIS_X);
$myPicture->myData->setAxisPosition(0,AXIS_POSITION_TOP);

/* Create the Y axis and the binded series */
for ($i=0;$i<=10;$i=$i+1) { 
	$myPicture->myData->addPoints(rand(1,20),"Probe 3"); 
}
$myPicture->myData->setSerieOnAxis("Probe 3",1);
$myPicture->myData->setAxisName(1,"Y-Index");
$myPicture->myData->setAxisXY(1,AXIS_Y);
$myPicture->myData->setAxisPosition(1,AXIS_POSITION_LEFT);

/* Create the 1st scatter chart binding */
$myPicture->myData->setScatterSerie("Probe 1","Probe 3",0);
$myPicture->myData->setScatterSerieDescription(0,"This year");
$myPicture->myData->setScatterSerieColor(0,array("R"=>0,"G"=>0,"B"=>0));
$myPicture->myData->setScatterSerieShape(0,SERIE_SHAPE_TRIANGLE);

/* Create the 2nd scatter chart binding */
$myPicture->myData->setScatterSerie("Probe 2","Probe 3",1);
$myPicture->myData->setScatterSerieDescription(1,"Last Year");
$myPicture->myData->setScatterSerieShape(1,SERIE_SHAPE_FILLEDTRIANGLE);

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,399,399,array("R"=>0,"G"=>0,"B"=>0));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Set the graph area */
$myPicture->setGraphArea(40,40,370,370);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture);

/* Draw the scale */
$scaleSettings = array("XMargin"=>15,"YMargin"=>15,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myScatter->drawScatterScale($scaleSettings);

/* Draw the legend */
$myScatter->drawScatterLegend(280,380,array("Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER));

/* Draw a scatter plot chart */
$myPicture->Antialias = TRUE;
$myScatter->drawScatterPlotChart();

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.example.drawScatterShape.png");

?>
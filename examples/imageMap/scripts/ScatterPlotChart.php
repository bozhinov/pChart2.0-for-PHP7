<?php   

/* pChart library inclusions */
chdir("../../");
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pScatter;
use pChart\pImageMap\pImageMapFile;

/* Create the pChart object */
/* 							X, Y, TransparentBackground, UniqueID, StorageFolder*/
$myPicture = new pImageMapFile(400,400,FALSE,"ScatterPlotChart","temp");

/* Retrieve the image map */
if (isset($_GET["ImageMap"])){
	$myPicture->dumpImageMap();
}

/* Create the X axis and the binded series */
$Points_1 = [];
$Points_2 = [];
$Points_3 = [];
for ($i=0;$i<=360;$i=$i+10) {
	$Points_1[] = cos(deg2rad($i))*20;
	$Points_2[] = sin(deg2rad($i))*20;
	$Points_3[] = $i;
}
$myPicture->myData->addPoints($Points_1,"Probe 1"); 
$myPicture->myData->addPoints($Points_2,"Probe 2"); 
$myPicture->myData->addPoints($Points_3,"Probe 3"); 

$myPicture->myData->setAxisName(0,"Index");
$myPicture->myData->setAxisXY(0,AXIS_X);
$myPicture->myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

/* Create the Y axis and the binded series */
$myPicture->myData->setSerieOnAxis("Probe 3",1);
$myPicture->myData->setAxisName(1,"Degree");
$myPicture->myData->setAxisXY(1,AXIS_Y);
$myPicture->myData->setAxisUnit(1,"°");
$myPicture->myData->setAxisPosition(1,AXIS_POSITION_RIGHT);

/* Create the 1st scatter chart binding */
$myPicture->myData->setScatterSerie("Probe 1","Probe 3",0);
$myPicture->myData->setScatterSerieDescription(0,"This year");
$myPicture->myData->setScatterSerieColor(0,new pColor(0,0,0));

/* Create the 2nd scatter chart binding */
$myPicture->myData->setScatterSerie("Probe 2","Probe 3",1);
$myPicture->myData->setScatterSerieDescription(1,"Last Year");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,400,400,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,400,400,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50), "EndColor"=>new pColor(1,138,68,50)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,399,399,["Color"=>new pColor(0,0,0)]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Set the graph area */
$myPicture->setGraphArea(50,30,350,330);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture);

/* Draw the scale */
$myScatter->drawScatterScale();

/* Turn on shadow computing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Turn off Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Draw a scatter plot chart */
$myScatter->drawScatterPlotChart(["RecordImageMap"=>TRUE]);

/* Draw the legend */
$myScatter->drawScatterLegend(260,375,["Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/ScatterPlotChart.png");

?>
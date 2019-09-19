<?php   
/* CAT:Polar and radars */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pRadar
};

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Create and populate the pData object */
$myPicture->myData->addPoints([10,20,30,40,50,60,70,80,90],"ScoreA");
$myPicture->myData->addPoints([20,40,50,12,10,30,40,50,60],"ScoreB");
$myPicture->myData->setSerieDescription("ScoreA","Coverage A");
$myPicture->myData->setSerieDescription("ScoreB","Coverage B");

/* Define the abscissa serie */
$myPicture->myData->addPoints([40,80,120,160,200,240,280,320,360],"Coord");
$myPicture->myData->setAbscissa("Coord");

/* Draw a solid background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

/* Overlay some gradient areas */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(194,231,44,50),"EndColor"=>new pColor(43,107,58,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL,["StartColor"=>new pColor(0),"EndColor"=>new pColor(50)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/PressStart2P-Regular.ttf","FontSize"=>6]);
$myPicture->drawText(10,15,"pRadar - Draw polar charts",["Color"=>new pColor(255)]);

/* Set the default font properties */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Cairo-Regular.ttf","FontSize"=>8,"Color"=>new pColor(80)]);

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Create the pRadar object */ 
$SplitChart = new pRadar($myPicture);

/* Draw a polar chart */ 
$SplitChart->myPicture->setGraphArea(10,25,340,225);
$SplitChart->drawPolar([
	"BackgroundGradient" => ["StartColor"=>new pColor(255),"EndColor"=>new pColor(207,227,125,50)],
	"FontName"=>"pChart/fonts/Cairo-Regular.ttf",
	"FontSize"=>7
]);

/* Draw a polar chart */ 

$SplitChart->myPicture->setGraphArea(350,25,690,225);
$SplitChart->drawPolar([
	"LabelPos"=>RADAR_LABELS_HORIZONTAL,
	"BackgroundGradient" => ["StartColor"=>new pColor(255,255,255,50),"EndColor"=>new pColor(32,109,174,30)],
	"AxisRotation"=>0,
	"DrawPoly"=>TRUE,
	"PolyAlpha"=>50,
	"FontName"=>"pChart/fonts/Cairo-Regular.ttf",
	"FontSize"=>7
]);

/* Write the chart legend */ 
$myPicture->setFontProperties(["FontSize"=>7]);
$myPicture->drawLegend(270,205,["Style"=>LEGEND_BOX,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.polar.png");

?>
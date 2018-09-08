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
$myPicture = new pDraw(300,300);

/* Create and populate the pData object */ 
$myPicture->myData->addPoints([10,20,30,40,50,60,70,80,90],"ScoreA");
$myPicture->myData->addPoints([20,40,50,30,10,30,40,50,60],"ScoreB");
$myPicture->myData->setSerieDescription("ScoreA","Coverage A");
$myPicture->myData->setSerieDescription("ScoreB","Coverage B");

/* Define the abscissa serie */
$myPicture->myData->addPoints([40,80,120,160,200,240,280,320,360],"Coord");
$myPicture->myData->setAbscissa("Coord");

$myPicture->drawGradientArea(0,0,300,300,DIRECTION_VERTICAL,  ["StartColor"=>new pColor(200,200,200,100),"EndColor"=>new pColor(240,240,240,100)]);
$myPicture->drawGradientArea(0,0,300,20, DIRECTION_HORIZONTAL,["StartColor"=>new pColor(30,30,30,100),"EndColor"=>new pColor(100,100,100,100)]);
$myPicture->drawLine(0,20,300,20,["Color"=>new pColor(255,255,255)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,299,299,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"pRadar - Draw radar charts",["Color"=>new pColor(255,255,255)]);

/* Set the default font properties */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"Color"=>new pColor(80,80,80)]);

/* Enable shadow computing */ 
$myPicture->setShadow(FALSE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Create the pRadar object */ 
$SplitChart = new pRadar($myPicture);

/* Draw a radar chart */ 
$SplitChart->myPicture->setGraphArea(10,25,290,290);
$SplitChart->drawPolar([
	"DrawPoly"=>TRUE,
	"WriteValues"=>TRUE,
	"ValueFontSize"=>8,
	"Layout"=>RADAR_LAYOUT_CIRCLE,
	"BackgroundGradient"=>["StartColor"=>new pColor(255,255,255,100),"EndColor"=>new pColor(207,227,125,50)]
]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.polar.values.png");

?>
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

/* Populate the pData object */
$myPicture->myData->addPoints([4,4,10,10,4,4,15,15,4,4,10,10,4,4,15,15,4,4,10,10,4,4,15,15],"ScoreA");
$myPicture->myData->setSerieDescription("ScoreA","Application A");
$myPicture->myData->setPalette("ScoreA",new pColor(150,5,217));

/* Define the abscissa serie */
$myPicture->myData->addPoints([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24],"Time");
$myPicture->myData->setAbscissa("Time");

$myPicture->drawGradientArea(0,0,300,300,DIRECTION_VERTICAL,  ["StartColor"=>new pColor(200,200,200,100),"EndColor"=>new pColor(240,240,240,100)]);
$myPicture->drawGradientArea(0,0,300,20, DIRECTION_HORIZONTAL,["StartColor"=>new pColor(30,30,30,100),   "EndColor"=>new pColor(100,100,100,100)]);
$myPicture->drawLine(0,20,300,20,["Color"=>new pColor(255,255,255)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,299,299,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pRadar - Draw radar charts",["Color"=>new pColor(255,255,255)]);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"Color"=>new pColor(80,80,80)));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,10)]);

/* Create the pRadar object */ 
$SplitChart = new pRadar($myPicture);

/* Draw a radar chart */ 
$myPicture->setGraphArea(10,25,290,290);
$SplitChart->drawRadar([
	"SkipLabels"=>3,
	"LabelMiddle"=>TRUE,
	"Layout"=>RADAR_LAYOUT_STAR,
	"BackgroundGradient"=>["StartColor"=>new pColor(255,255,255,100),"EndColor"=>new pColor(207,227,125,50)]
]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.radar.labels.png");

?>
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
$myPicture->myData->addPoints([1,5,6,1,5,7,3,6,5,4,1,0],"ScoreA");
$myPicture->myData->setSerieDescription("ScoreA","Application A");
$myPicture->myData->setPalette("ScoreA",new pColor(150,5,217));

/* Define the abscissa serie */
$myPicture->myData->addPoints([1,2,3,4,5,6,7,8,9,10,11,12],"Time");
$myPicture->myData->setAbscissa("Time");

/* Draw a solid background */
$myPicture->drawFilledRectangle(0,0,300,300,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

/* Overlay some gradient areas */
$myPicture->drawGradientArea(0,0,300,300,DIRECTION_VERTICAL,  ["StartColor"=>new pColor(194,231,44,50),"EndColor"=>new pColor(43,107,58,50)]);
$myPicture->drawGradientArea(0,0,300,20, DIRECTION_HORIZONTAL,["StartColor"=>new pColor(30,30,30,100), "EndColor"=>new pColor(100,100,100,100)]);

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
$SplitChart->myPicture->setGraphArea(10,25,290,290);
$SplitChart->drawRadar([
	"FixedMax"=>10,
	"AxisRotation"=>-60,
	"Layout"=>RADAR_LAYOUT_STAR,
	"BackgroundGradient"=>["StartColor"=>new pColor(255,255,255,100),"EndColor"=>new pColor(207,227,125,50)]
	]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.fixedmax.png");

?>
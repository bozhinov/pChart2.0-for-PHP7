<?php   
/* CAT:Surface chart*/

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pSurface
};

/* Create the pChart object */
$myPicture = new pDraw(400,400);

/* Create a solid background */
$myPicture->drawFilledRectangle(0,0,400,400,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

/* Do a gradient overlay */
$myPicture->drawGradientArea(0,0,400,400,DIRECTION_VERTICAL,["StartColor"=>new pColor(194,231,44,50), "EndColor"=>new pColor(43,107,58,50)]);
$myPicture->drawGradientArea(0,0,400,20, DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,100), "EndColor"=>new pColor(50,50,50,100)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,399,399,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pSurface() :: 2D surface charts",["Color"=>new pColor(255,255,255)]);

/* Define the charting area */
$myPicture->setGraphArea(20,40,380,380);
$myPicture->drawFilledRectangle(20,40,380,380,["Color"=>new pColor(255,255,255,20),"Surrounding"=>-200]);

$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1]);

/* Create the surface object */
$mySurface = new pSurface($myPicture);

/* Set the grid size */
$mySurface->setGrid(20,20);

/* Write the axis labels */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
$mySurface->writeXLabels();
$mySurface->writeYLabels();

/* Add random values */
for($i=0; $i<=50; $i++) {
	$mySurface->addPoint(rand(0,20),rand(0,20),rand(0,100));
}

/* Compute the missing points */
$mySurface->computeMissing();

/* Draw the surface chart */
$mySurface->drawSurface(["Border"=>TRUE,"Surrounding"=>40]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.surface.png");

?>
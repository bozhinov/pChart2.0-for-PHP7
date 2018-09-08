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
$myPicture = new pDraw(210,100);

/* Create a solid background */
$myPicture->drawFilledRectangle(0,0,210,100,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

/* Do a gradient overlay */
$myPicture->drawGradientArea(0,0,210,100,DIRECTION_VERTICAL,["StartColor"=>new pColor(194,231,44,50), "EndColor"=>new pColor(43,107,58,50)]);
$myPicture->drawGradientArea(0,0,210,20, DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,100), "EndColor"=>new pColor(50,50,50,100)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,209,99,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pSurface() :: Surface charts",["Color"=>new pColor(255,255,255)]);

/* Define the charting area */
$myPicture->setGraphArea(50,60,180,80);
$myPicture->drawFilledRectangle(50,60,180,80,["Color"=>new pColor(255,255,255,20),"Surrounding"=>-200]);

$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1]);

/* Create the surface object */
$mySurface = new pSurface($myPicture);

/* Set the grid size */
$mySurface->setGrid(9,1);

/* Write the axis labels */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Bedizen.ttf","FontSize"=>7]);
$mySurface->writeXLabels(array("Angle"=>45,"Labels"=>["Run 1","Run 2","Run 3","Run 4","Run 5","Run 6","Run 7","Run 8","Run 9","Run 10"]));
$mySurface->writeYLabels(array("Labels"=>["Probe 1","Probe 2"]));

/* Add random values */
for($i=0; $i<=10; $i++) { 
	$mySurface->addPoint($i,rand(0,1),rand(0,100));
}

/* Draw the surface chart */
$mySurface->drawSurface(["Border"=>TRUE,"Surrounding"=>40]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.surface.simple.png");

?>
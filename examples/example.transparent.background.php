<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230,TRUE);

/* Draw rounded filled rectangles */
$myPicture->drawRoundedFilledRectangle(10,25,70,55,5,  ["Color"=>new pColor(209,31,27,50), "Surrounding"=>30]);
$myPicture->drawRoundedFilledRectangle(10,85,70,115,5, ["Color"=>new pColor(209,125,27,50),"Surrounding"=>30]);
$myPicture->drawRoundedFilledRectangle(10,135,70,165,5,["Color"=>new pColor(209,198,27,50),"Surrounding"=>30]);
$myPicture->drawRoundedFilledRectangle(10,185,70,215,5,["Color"=>new pColor(134,209,27,50),"Surrounding"=>30]);

/* Enable shadow computing */
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,20)]);

/* Draw a rounded filled rectangle */
$myPicture->drawRoundedFilledRectangle(100,20,680,210,20,["Color"=>new pColor(209,198,27,100),"Surrounding"=>30,"Radius"=>20]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.transparent.background.png");

?>
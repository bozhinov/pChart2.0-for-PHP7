<?php   
/* CAT:Drawing */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"drawGradientArea() - Transparency & colors",["Color"=>new pColor(255,255,255)]);

/* Draw a gradient area */ 
/* $End color is ignored and Level is applied to $Start color */
$EndColor = new pColor(0,0,0);
$myPicture->drawGradientArea(20,60,400,170,	DIRECTION_HORIZONTAL, ["StartColor"=>new pColor(181,209,27),"EndColor"=>$EndColor],$Levels = -50);
$myPicture->drawGradientArea(30,30,200,200,	DIRECTION_VERTICAL,	  ["StartColor"=>new pColor(209,134,27,30),"EndColor"=>$EndColor],$Levels = -50);
$myPicture->drawGradientArea(480,50,650,80,	DIRECTION_HORIZONTAL, ["StartColor"=>new pColor(209,31,27), "EndColor"=>$EndColor],$Levels = 50);
$myPicture->drawGradientArea(480,90,650,120,DIRECTION_VERTICAL,	  ["StartColor"=>new pColor(209,125,27),"EndColor"=>$EndColor],$Levels = 50);
$myPicture->drawGradientArea(480,130,650,160,DIRECTION_HORIZONTAL,["StartColor"=>new pColor(209,198,27),"EndColor"=>$EndColor],$Levels = 50);
$myPicture->drawGradientArea(480,170,650,200,DIRECTION_HORIZONTAL,["StartColor"=>new pColor(134,209,27),"EndColor"=>$EndColor],$Levels = 50);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawGradientArea.png");

?>
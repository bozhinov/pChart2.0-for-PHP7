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
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"drawCircle() - Transparency & colors",["Color"=>new pColor(255,255,255)]);

/* Draw some circles */ 
$myPicture->drawCircle(100,125,50,50,["Color"=>new pColor(213,226,0,100)]);
$myPicture->drawCircle(140,125,50,50,["Color"=>new pColor(213,226,0,70)]);
$myPicture->drawCircle(180,125,50,50,["Color"=>new pColor(213,226,0,40)]);
$myPicture->drawCircle(220,125,50,50,["Color"=>new pColor(213,226,0,20)]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);

/* Draw a customized circles */ 
$myPicture->drawCircle(480,60,20,20, ["Color"=>new pColor(209,31,27,100)]);
$myPicture->drawCircle(480,100,30,20,["Color"=>new pColor(209,125,27,100)]);
$myPicture->drawCircle(480,140,40,20,["Color"=>new pColor(209,198,27,100),"Ticks"=>4]);
$myPicture->drawCircle(480,180,50,20,["Color"=>new pColor(134,209,27,100),"Ticks"=>4]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawCircle.png");

?>
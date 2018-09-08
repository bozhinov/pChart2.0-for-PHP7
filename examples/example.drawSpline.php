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
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL, ["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawSpline() - for smooth line drawing",["Color"=>new pColor(255,255,255)]);

/* Enable shadow computing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);

/* Draw a spline */
$SplineSettings = ["Color"=>new pColor(255,255,255),"ShowControl"=>TRUE];
$Coordinates = array([40,80],[280,60],[340,166],[590,120]);
$myPicture->drawSpline($Coordinates,$SplineSettings);

/* Draw a spline */
$SplineSettings = ["Color"=>new pColor(255,255,255),"ShowControl"=>TRUE,"Ticks"=>4];
$Coordinates = array([250,50],[250,180],[350,180],[350,50]);
$myPicture->drawSpline($Coordinates,$SplineSettings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawSpline.png");

?>
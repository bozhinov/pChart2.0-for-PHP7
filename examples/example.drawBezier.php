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
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawBezier() - some cubic curves",["Color"=>new pColor(255,255,255)]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);

/* Draw one bezier curve */ 
$myPicture->drawBezier(20,40,280,170,130,160,160,60,["Color"=>new pColor(255,255,255),"ShowControl"=>TRUE]);

/* Draw one bezier curve */ 
$myPicture->drawBezier(360,120,630,120,430,50,560,190,["Color"=>new pColor(255,255,255),"ShowControl"=>TRUE,"Ticks"=>4,"DrawArrow"=>TRUE,"ArrowTwoHeads"=>TRUE]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawBezier.png");

?>
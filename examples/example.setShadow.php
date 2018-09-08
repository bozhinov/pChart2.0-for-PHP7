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
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"setShadow() - Add shadows",["Color"=>new pColor(255,255,255)]);

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,20)]);

/* Draw a filled circle */ 
$myPicture->drawFilledCircle(90,120,30,["Color"=>new pColor(201,230,40,100),"Surrounding"=>30]);

/* Draw a filled rectangle */ 
$myPicture->drawFilledRectangle(160,90,280,150,["Color"=>new pColor(231,197,40,100),"Surrounding"=>30]);

/* Draw a filled rounded rectangle */ 
$myPicture->drawRoundedFilledRectangle(320,90,440,150,5,["Color"=>new pColor(231,102,40,100),"Surrounding"=>70]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.setShadow.png");

?>
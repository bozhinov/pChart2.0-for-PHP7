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
$myPicture->drawText(10,13,"drawText() - add some text to your charts",["Color"=>new pColor(255,255,255)]);

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);

/* Write some text */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/advent_light.ttf","FontSize"=>20));

$myPicture->drawText(60,115,"10 degree text",["Color"=>new pColor(255,255,255),"Angle"=>10]);
$myPicture->drawText(220,130,"Simple text",  ["Color"=>new pColor(0,0,0),"Angle"=>0,"FontSize"=>40]);
$myPicture->drawText(500,170,"Vertical Text",["Color"=>new pColor(200,100,0),"Angle"=>90,"FontSize"=>14]);

/* Write some text */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Bedizen.ttf","FontSize"=>6));
$myPicture->drawText(220,160,"Encapsulated text",["DrawBox"=>TRUE,"BoxRounded"=>TRUE,"Color"=>new pColor(0,0,0),"Angle"=>0,"FontSize"=>10]);

/* Write some text */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>6));
$myPicture->drawText(220,195,"Text in a box",["DrawBox"=>TRUE,"Color"=>new pColor(0,0,0),"Angle"=>0,"FontSize"=>10]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawText.png");

?>
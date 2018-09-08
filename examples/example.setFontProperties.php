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
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"setFontProperties() - set default font properties",["Color"=>new pColor(255,255,255)]);

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);

/* Write some text */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/advent_light.ttf","FontSize"=>20]);
$myPicture->drawText(60,115,"10 degree text",["Angle"=>10]);

/* Write some text */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>20]);
$myPicture->drawText(75,130,"10 degree text",["Angle"=>10]);

/* Write some text */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/GeosansLight.ttf","FontSize"=>20]);
$myPicture->drawText(90,145,"10 degree text",["Angle"=>10]);

/* Write some text */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>20]);
$myPicture->drawText(105,160,"10 degree text",["Angle"=>10]);

/* Write some text */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>30,"Color"=>new pColor(231,50,36)]);
$myPicture->drawText(340,90,"Some big red text");

/* Write some text */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6,"Color"=>new pColor(29,70,111)]);
$myPicture->drawText(340,100,"Some blue text");

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.setFontProperties.png");

?>
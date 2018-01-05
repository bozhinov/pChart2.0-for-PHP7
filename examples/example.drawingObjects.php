<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");
require_once("myColors.php");

use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Define default font settings */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>14]); 

/* Create the background */
$myPicture->drawGradientArea(0,0,500,230,DIRECTION_HORIZONTAL,myColors::myGreenGradient());
$myPicture->drawFilledRectangle(500,0,700,230,["Color"=>myColors::LightGreen($Alpha=100)]);

/* Enable shadow computing on a (+1,+1) basis */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>myColors::Black($Alpha=20)]);

/* Draw the left area */
$myPicture->drawRoundedFilledRectangle(-5,0,20,240,10,["Color"=>myColors::LightGreen()]); 
$myPicture->drawText(10,220,"My first chart",["Color"=>myColors::White(),"Angle"=>90,"Align"=>TEXT_ALIGN_MIDDLELEFT]);

/* Draw the right area */
$myPicture->drawFilledRectangle(510,10,689,219,["Color"=>myColors::LighterGreen($Alpha=100),"Surrounding"=>20,"Ticks"=>2]);

/* Write the legend */
$myPicture->drawText(600,30,"Weather data",["Color"=>myColors::White(),"Align"=>TEXT_ALIGN_MIDDLEMIDDLE]);
$TextSettings = ["Color"=>myColors::DarkGreen($Alpha=100),"Align"=>TEXT_ALIGN_TOPLEFT,"FontSize"=>11]; 
$myPicture->drawText(520,45,"The   data  shown  here   has   been",$TextSettings);
$myPicture->drawText(520,60,"collected from European locations",$TextSettings);
$myPicture->drawText(520,75,"by the French NAVI system.",$TextSettings);
$myPicture->drawFromPNG(540,90,"examples/resources/blocnote.png");

/* Disable shadow computing  */
$myPicture->setShadow(FALSE);

/* Draw the picture border */
$myPicture->drawRectangle(0,0,699,229,["Color"=>myColors::LightGreen()]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawingObjects.png");

?>
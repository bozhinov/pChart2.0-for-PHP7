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
$myPicture->drawText(10,13,"drawFromPNG() - add pictures to your charts",["Color"=>new pColor(255,255,255)]);

/* Turn off shadow computing */ 
$myPicture->setShadow(FALSE);

/* Draw a PNG object */
$myPicture->drawFromPNG(180,50,"examples/resources/hologram.png");

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);

/* Draw a PNG object */
$myPicture->drawFromPNG(400,50,"examples/resources/blocnote.png");

/* Write the legend */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);
$TextSettings = ["Color"=>new pColor(255,255,255),"FontSize"=>10,"FontName"=>"pChart/fonts/MankSans.ttf","Align"=>TEXT_ALIGN_BOTTOMMIDDLE];
$myPicture->drawText(240,190,"          Without shadow\r\n(only PNG alpha channels)",$TextSettings);
$myPicture->drawText(460,200,"With enhanced shadow",$TextSettings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawFromPNG.png");

?>
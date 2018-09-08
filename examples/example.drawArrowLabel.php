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
$myPicture->drawText(10,13,"drawArrowLabel() - Adaptive label positioning",["Color"=>new pColor(255,255,255)]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,new pColor(0,0,0,10)]);

/* Draw an arrow with a 45 degree angle */ 
$myPicture->drawArrowLabel(348,113,"Blue",["FillColor"=>new pColor(37,78,117),"Length"=>40,"Angle"=>45]);

/* Draw an arrow with a 135 degree angle */ 
$myPicture->drawArrowLabel(348,117,"Red",["FillColor"=>new pColor(188,49,42),"Length"=>40,"Angle"=>135,"Position"=>POSITION_BOTTOM,"Ticks"=>2]);

/* Draw an arrow with a 225 degree angle */ 
$myPicture->drawArrowLabel(352,117,"Green",["FillColor"=>new pColor(51,119,35),"Length"=>40,"Angle"=>225,"Position"=>POSITION_BOTTOM,"Ticks"=>3]);

/* Draw an arrow with a 315 degree angle */ 
$myPicture->drawArrowLabel(352,113,"Yellow",["FillColor"=>new pColor(239,231,97),"Length"=>40,"Angle"=>315,"Ticks"=>4]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawArrowLabel.png");

?>
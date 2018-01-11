<?php
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(550,175);

/* Create a solid background */
$myPicture->drawFilledRectangle(0,0,550,175,["Color"=>new pColor(183,161,71), "Dash"=>TRUE, "DashColor"=>new pColor(203,181,91)]);

/* Do a gradient overlay */;
$myPicture->drawGradientArea(0,0,550,175,DIRECTION_VERTICAL, ["StartColor"=>new pColor(231,228,155,50), "EndColor"=>new pColor(138,91,10,50)]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>20));

/* Draw the text box */
$myPicture->setShadow(FALSE);
$myPicture->drawFilledRectangle(141,72,415,126,["Color"=>new pColor(230,230,230,20)]);
$myPicture->drawRectangle(141,72,415,126,["Color"=>new pColor(50,50,50)]);

/* Write the text */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);
$myPicture->drawText(144,125,"My text box",["Color"=>new pColor(201,230,40),"FontSize"=>40]);

/* Prepare and draw the markers */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);
$MyMarker = ["Color"=>new pColor(255,0,0),"BorderColor"=>new pColor(0,0,0),"Size"=>4];

$myPicture->drawRectangleMarker(141,72,$MyMarker);
$myPicture->drawRectangleMarker(141,101,$MyMarker);
$myPicture->drawRectangleMarker(141,126,$MyMarker);

$myPicture->drawRectangleMarker(274,72,$MyMarker);
$myPicture->drawRectangleMarker(274,101,$MyMarker);
$myPicture->drawRectangleMarker(274,126,$MyMarker);

$myPicture->drawRectangleMarker(415,72,$MyMarker);
$myPicture->drawRectangleMarker(415,101,$MyMarker);
$myPicture->drawRectangleMarker(415,126,$MyMarker);

/* Change the font settings */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>7));
$myPicture->setShadow(FALSE);

/* Write the arrows */
$myPicture->drawArrowLabel(139,72,"TEXT_ALIGN_TOPLEFT",["Length"=>20,"Angle"=>45,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(139,101,"TEXT_ALIGN_MIDDLELEFT",["Length"=>20,"Angle"=>90,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(139,128,"TEXT_ALIGN_BOTTOMLEFT",["Length"=>20,"Angle"=>135,"RoundPos"=>TRUE]);

$myPicture->drawArrowLabel(274,72,"TEXT_ALIGN_TOPMIDDLE",["Length"=>20,"Angle"=>45,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(274,101,"TEXT_ALIGN_MIDDLEMIDDLE",["Length"=>90,"Angle"=>315,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(274,128,"TEXT_ALIGN_BOTTOMMIDDLE",["Length"=>20,"Angle"=>225,"RoundPos"=>TRUE]);

$myPicture->drawArrowLabel(415,72,"TEXT_ALIGN_TOPRIGHT",["Length"=>20,"Angle"=>315,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(415,101,"TEXT_ALIGN_MIDDLERIGHT",["Length"=>20,"Angle"=>270,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(415,128,"TEXT_ALIGN_BOTTOMRIGHT",["Length"=>20,"Angle"=>225,"RoundPos"=>TRUE]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.text.alignment.png");

?>
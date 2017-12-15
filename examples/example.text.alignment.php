<?php
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(550,175);

/* Create a solid background */
$Settings = array("R"=>183, "G"=>161, "B"=>71, "Dash"=>1, "DashR"=>203, "DashG"=>181, "DashB"=>91);
$myPicture->drawFilledRectangle(0,0,550,175,$Settings);

/* Do a gradient overlay */
$Settings = array("StartR"=>231, "StartG"=>228, "StartB"=>155, "EndR"=>138, "EndG"=>91, "EndB"=>10, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,550,175,DIRECTION_VERTICAL,$Settings);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/calibri.ttf","FontSize"=>20));

/* Draw the text box */
$myPicture->setShadow(FALSE);
$myPicture->drawFilledRectangle(141,77,393,126,["Alpha"=>20,"R"=>230,"G"=>230,"B"=>230]);
$myPicture->drawRectangle(141,77,393,126,["R"=>50,"G"=>50,"B"=>50]);

/* Write the text */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20]);
$myPicture->drawText(144,125,"My text box",["R"=>201,"G"=>230,"B"=>40,"FontSize"=>40]);

/* Prepare and draw the markers */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20]);
$MyMarker = array("R"=>255,"G"=>0,"B"=>0,"BorderR"=>0,"BorderB"=>0,"BorderG"=>0,"Size"=>4);

$myPicture->drawRectangleMarker(141,77,$MyMarker);
$myPicture->drawRectangleMarker(141,101,$MyMarker);
$myPicture->drawRectangleMarker(141,126,$MyMarker);

$myPicture->drawRectangleMarker(260,77,$MyMarker);
$myPicture->drawRectangleMarker(260,101,$MyMarker);
$myPicture->drawRectangleMarker(260,126,$MyMarker);

$myPicture->drawRectangleMarker(393,77,$MyMarker);
$myPicture->drawRectangleMarker(393,101,$MyMarker);
$myPicture->drawRectangleMarker(393,126,$MyMarker);

/* Change the font settings */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/calibri.ttf","FontSize"=>7));
$myPicture->setShadow(FALSE);

/* Write the arrows */
$myPicture->drawArrowLabel(139,75,"TEXT_ALIGN_TOPLEFT",["Length"=>20,"Angle"=>45,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(139,101,"TEXT_ALIGN_MIDDLELEFT",["Length"=>20,"Angle"=>90,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(139,128,"TEXT_ALIGN_BOTTOMLEFT",["Length"=>20,"Angle"=>135,"RoundPos"=>TRUE]);

$myPicture->drawArrowLabel(260,75,"TEXT_ALIGN_TOPMIDDLE",["Length"=>20,"Angle"=>45,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(260,101,"TEXT_ALIGN_MIDDLEMIDDLE",["Length"=>90,"Angle"=>315,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(260,128,"TEXT_ALIGN_BOTTOMMIDDLE",["Length"=>20,"Angle"=>225,"RoundPos"=>TRUE]);

$myPicture->drawArrowLabel(395,75,"TEXT_ALIGN_TOPRIGHT",["Length"=>20,"Angle"=>315,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(395,101,"TEXT_ALIGN_MIDDLERIGHT",["Length"=>20,"Angle"=>270,"RoundPos"=>TRUE]);
$myPicture->drawArrowLabel(395,128,"TEXT_ALIGN_BOTTOMRIGHT",["Length"=>20,"Angle"=>225,"RoundPos"=>TRUE]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.spring.relations.png");

?>
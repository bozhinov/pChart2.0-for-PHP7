<?php   
/* CAT:Progress bars */

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
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf", "FontSize"=>6));
$myPicture->drawText(10,13, "drawProgress() - Simple progress bars",["Color"=>new pColor(255,255,255)]);

/* Set the font & shadow options */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf", "FontSize"=>10));
$myPicture->setShadow(TRUE,["X"=>1, "Y"=>1, "Color"=>new pColor(0,0,0,20)]);

/* Draw a progress bar */ 
$progressOptions = array("Color"=>new pColor(209,31,27), "Surrounding"=>20, "BoxBorderColor"=>new pColor(0,0,0), "BoxBackColor"=>new pColor(255,255,255), "FadeColor"=>new pColor(206,133,30), "ShowLabel"=>TRUE);
$myPicture->drawProgress(40,60,77,$progressOptions);

/* Draw a progress bar */ 
$progressOptions = array("Width"=>165, "Color"=>new pColor(209,125,27), "Surrounding"=>20, "BoxBorderColor"=>new pColor(0,0,0), "BoxBackColor"=>new pColor(255,255,255),"NoAngle"=>TRUE, "ShowLabel"=>TRUE, "LabelPos"=>LABEL_POS_RIGHT);
$myPicture->drawProgress(40,100,50,$progressOptions);

/* Draw a progress bar */ 
$progressOptions = array("Width"=>165, "Color"=>new pColor(209,198,27), "Surrounding"=>20, "BoxBorderColor"=>new pColor(0,0,0), "BoxBackColor"=>new pColor(255,255,255), "ShowLabel"=>TRUE, "LabelPos"=>LABEL_POS_LEFT);
$myPicture->drawProgress(75,140,25,$progressOptions);

/* Draw a progress bar */ 
$progressOptions = array("Width"=>400, "Color"=>new pColor(134,209,27), "Surrounding"=>20, "BoxBorderColor"=>new pColor(0,0,0), "BoxBackColor"=>new pColor(255,255,255), "FadeColor"=>new pColor(206,133,30), "ShowLabel"=>TRUE, "LabelPos"=>LABEL_POS_CENTER);
$myPicture->drawProgress(40,180,80,$progressOptions);

/* Draw a progress bar */ 
$progressOptions = array("Width"=>20, "Height"=>150, "Color"=>new pColor(209,31,27), "Surrounding"=>20, "BoxBorderColor"=>new pColor(0,0,0), "BoxBackColor"=>new pColor(255,255,255), "FadeColor"=>new pColor(206,133,30), "ShowLabel"=>TRUE, "Orientation"=>ORIENTATION_VERTICAL, "LabelPos"=>LABEL_POS_BOTTOM);
$myPicture->drawProgress(500,200,77,$progressOptions);

/* Draw a progress bar */ 
$progressOptions = array("Width"=>20, "Height"=>150, "Color"=>new pColor(209,125,27), "Surrounding"=>20, "BoxBorderColor"=>new pColor(0,0,0), "BoxBackColor"=>new pColor(255,255,255),"NoAngle"=>TRUE, "ShowLabel"=>TRUE, "Orientation"=>ORIENTATION_VERTICAL, "LabelPos"=>LABEL_POS_TOP);
$myPicture->drawProgress(540,200,50,$progressOptions);

/* Draw a progress bar */ 
$progressOptions = array("Width"=>20, "Height"=>150, "Color"=>new pColor(209,198,27), "Surrounding"=>20, "BoxBorderColor"=>new pColor(0,0,0), "BoxBackColor"=>new pColor(255,255,255), "ShowLabel"=>TRUE, "Orientation"=>ORIENTATION_VERTICAL, "LabelPos"=>LABEL_POS_INSIDE);
$myPicture->drawProgress(580,200,25,$progressOptions);

/* Draw a progress bar */ 
$progressOptions = array("Width"=>20, "Height"=>150, "Color"=>new pColor(134,209,27), "Surrounding"=>20, "BoxBorderColor"=>new pColor(0,0,0), "BoxBackColor"=>new pColor(255,255,255), "FadeColor"=>new pColor(206,133,30), "ShowLabel"=>TRUE, "Orientation"=>ORIENTATION_VERTICAL, "LabelPos"=>LABEL_POS_CENTER);
$myPicture->drawProgress(620,200,80,$progressOptions);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawProgress.png");

?>
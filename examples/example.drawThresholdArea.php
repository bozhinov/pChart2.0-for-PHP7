<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */ 
$myPicture->myData->addPoints([24,-25,26,25,25],"Temperature");
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL, ["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"drawThresholdArea() - draw threshold areas in the charting area",["Color"=>new pColor(255,255,255)]);

/* Write the chart title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);
$myPicture->drawText(250,55,"My chart title",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Draw the scale and do some cosmetics */ 
$myPicture->setGraphArea(60,60,450,190);
$myPicture->drawFilledRectangle(70,70,440,180,["Color"=>new pColor(255,255,255,19),"Surrounding"=>-200]);
$myPicture->drawScale(["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"DrawSubTicks"=>TRUE]);

/* Draw one static threshold area */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1]);
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>10]);
$myPicture->drawThresholdArea(0,100,["AreaName"=>"Test Zone","Color"=>new pColor(226,194,54,40)]);
$myPicture->setShadow(FALSE);

/* Set the font properties */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);

/* Draw the scale and do some cosmetics */ 
$myPicture->setGraphArea(500,60,670,190);
$myPicture->drawFilledRectangle(505,65,665,185,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["XMargin"=>5,"YMargin"=>5,"Floating"=>TRUE,"Pos"=>SCALE_POS_TOPBOTTOM,"DrawSubTicks"=>TRUE]);

/* Draw one static threshold area */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1]);
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>10]);
$myPicture->drawThresholdArea(5,15,["NameColor"=>new pColor(0,0,0),"AreaName"=>"Test Zone","Color"=>new pColor(206,231,64,20)]);
$myPicture->setShadow(FALSE);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawThresholdArea.png");

?>
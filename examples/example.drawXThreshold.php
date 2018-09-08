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
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawThreshold() - draw a threshold in the charting area",["Color"=>new pColor(255,255,255)]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(250,55,"My chart title",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Draw the scale and do some cosmetics */ 
$myPicture->setGraphArea(60,60,450,190);
$myPicture->drawFilledRectangle(70,70,440,180,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"DrawSubTicks"=>TRUE]);
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);

/* Draw static thresholds */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10]);
$myPicture->drawXThreshold(["Feb"],["ValueIsLabel"=>TRUE,"WriteCaption"=>TRUE,"Caption"=>"Step 1","Color"=>new pColor(255,0,0,70), "BoxBorderColor" => new pColor(0,0,0,50),"Ticks"=>1]);
$myPicture->drawXThreshold([2],["WriteCaption"=>TRUE,"Caption"=>"Step 2","Ticks"=>2,"Color"=>new pColor(0,0,255,70), "BoxBorderColor" => new pColor(0,0,0,50)]);

/* Disable shadow computing */ 
$myPicture->setShadow(FALSE);

/* Draw the scale and do some cosmetics */ 
$myPicture->setGraphArea(500,60,670,190);
$myPicture->drawFilledRectangle(505,65,665,185,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);
$myPicture->drawScale(["XMargin"=>5,"YMargin"=>5,"Floating"=>TRUE,"Pos"=>SCALE_POS_TOPBOTTOM,"DrawSubTicks"=>TRUE]);
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]); 

/* Draw static thresholds */ 
$myPicture->drawXThreshold([1,3],["Color"=>new pColor(255,0,0,70),"Ticks"=>1]);
$myPicture->drawXThreshold([2],["Color"=>new pColor(0,0,255,70),"Ticks"=>2]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawXThreshold.png");

?>
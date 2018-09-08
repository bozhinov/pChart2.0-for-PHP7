<?php
/* CAT:Stock chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pStock
};

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([34,55,15,62,38,42],"Open");
$myPicture->myData->addPoints([42,25,40,38,49,36],"Close");
$myPicture->myData->addPoints([27,14,12,25,32,32],"Min");
$myPicture->myData->addPoints([45,59,47,65,64,48],"Max");
$myPicture->myData->setAxisDisplay(0,AXIS_FORMAT_CURRENCY,"$");

$myPicture->myData->addPoints(["8h","10h","12h","14h","16h","18h"],"Time");
$myPicture->myData->setAbscissa("Time");

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL, ["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the title */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(60,45,"Stock price",["FontSize"=>28,"Align"=>TEXT_ALIGN_BOTTOMLEFT]);

/* Draw the 1st scale */
$myPicture->setGraphArea(60,60,450,190);
$myPicture->drawFilledRectangle(60,60,450,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);

/* Draw the 1st stock chart */
$mystockChart = new pStock($myPicture);
$mystockChart->myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,30)]);
$mystockChart->drawStockChart();

/* Reset the display mode because of the graph small size */
$myPicture->myData->setAxisDisplay(0,AXIS_FORMAT_DEFAULT);

/* Draw the 2nd scale */
$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(500,60,670,190);
$myPicture->drawFilledRectangle(500,60,670,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["Pos"=>SCALE_POS_TOPBOTTOM,"DrawSubTicks"=>TRUE]);

/* Draw the 2nd stock chart */
$mystockChart = new pStock($myPicture);
$mystockChart->myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,30)]);
$mystockChart->drawStockChart();

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawStockChart.png");

?>
<?php
/* CAT:Stock chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
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
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);

/* Draw the border */
$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

/* Write the title */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(60,45,"Stock price",array("FontSize"=>28,"Align"=>TEXT_ALIGN_BOTTOMLEFT));

/* Draw the 1st scale */
$myPicture->setGraphArea(60,60,450,190);
$myPicture->drawFilledRectangle(60,60,450,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
$myPicture->drawScale(array("DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE));

/* Draw the 1st stock chart */
$mystockChart = new pStock($myPicture);
$mystockChart->myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>30));
$mystockChart->drawStockChart();

/* Reset the display mode because of the graph small size */
$myPicture->myData->setAxisDisplay(0,AXIS_FORMAT_DEFAULT);

/* Draw the 2nd scale */
$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(500,60,670,190);
$myPicture->drawFilledRectangle(500,60,670,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
$myPicture->drawScale(array("Pos"=>SCALE_POS_TOPBOTTOM,"DrawSubTicks"=>TRUE));

/* Draw the 2nd stock chart */
$mystockChart = new pStock($myPicture);
$mystockChart->myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>30));
$mystockChart->drawStockChart();

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawStockChart.png");

?>
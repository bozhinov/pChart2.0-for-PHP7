<?php   
/* CAT:Scatter chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pScatter;

/* Create the pChart object */
$myPicture = new pDraw(400,400);

/* Create the X axis and the binded series */
$myPicture->myData->addPoints(array(3,12,15,8,5,-5),"X Values");
$myPicture->myData->setAxisName(0,"X Values");
$myPicture->myData->setAxisXY(0,AXIS_X);
$myPicture->myData->setAxisDisplay(0,AXIS_FORMAT_TIME,"i:s");
$myPicture->myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

/* Create the Y axis and the binded series */
$myPicture->myData->addPoints(array(2,7,5,18,19,22),"Y Values");
$myPicture->myData->setSerieOnAxis("Y Values",1);
$myPicture->myData->setAxisName(1,"Y Values");
$myPicture->myData->setAxisXY(1,AXIS_Y);

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,400,400,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,400,400,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,400,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawScatterScale() - Draw the scatter chart scale",array("R"=>255,"G"=>255,"B"=>255));

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,399,399,array("R"=>0,"G"=>0,"B"=>0));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Set the graph area */
$myPicture->setGraphArea(50,50,350,350);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture);

/* Draw the scale */
$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>3600,"Rows"=>12,"RowHeight"=>300),1=>array("Min"=>0,"Max"=>100));
$ScaleSettings  = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"DrawSubTicks"=>TRUE);
$myScatter->drawScatterScale($ScaleSettings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawScatterScale.manual.png");

?>
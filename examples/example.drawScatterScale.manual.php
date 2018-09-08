<?php   
/* CAT:Scatter chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pScatter;

/* Create the pChart object */
$myPicture = new pDraw(400,400);

/* Create the X axis and the binded series */
$myPicture->myData->addPoints([3,12,15,8,5,-5],"X Values");
$myPicture->myData->setAxisName(0,"X Values");
$myPicture->myData->setAxisXY(0,AXIS_X);
$myPicture->myData->setAxisDisplay(0,AXIS_FORMAT_TIME,"i:s");
$myPicture->myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

/* Create the Y axis and the binded series */
$myPicture->myData->addPoints([2,7,5,18,19,22],"Y Values");
$myPicture->myData->setSerieOnAxis("Y Values",1);
$myPicture->myData->setAxisName(1,"Y Values");
$myPicture->myData->setAxisXY(1,AXIS_Y);

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,400,400,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,400,400,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,400,20,DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawScatterScale() - Draw the scatter chart scale",["Color"=>new pColor(255,255,255)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,399,399,["Color"=>new pColor(0,0,0)]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Set the graph area */
$myPicture->setGraphArea(50,50,350,350);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture);

/* Draw the scale */
$AxisBoundaries = array(0=>["Min"=>0,"Max"=>3600,"Rows"=>12,"RowHeight"=>300],1=>["Min"=>0,"Max"=>100]);
$ScaleSettings  = ["Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"DrawSubTicks"=>TRUE];
$myScatter->drawScatterScale($ScaleSettings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawScatterScale.manual.png");

?>
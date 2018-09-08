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
$myPicture->myData->addPoints([-4,VOID,VOID,12,8,3],"Probe 1");
$myPicture->myData->addPoints([3,12,15,8,5,-5],"Probe 2");
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->setAxisXY(0,AXIS_X);
$myPicture->myData->setAxisUnit(0,"°C");
$myPicture->myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

/* Create the Y axis and the binded series */
$myPicture->myData->addPoints([2,7,5,18,19,22],"Probe 3");
$myPicture->myData->setSerieOnAxis("Probe 3",1);
$myPicture->myData->setAxisName(1,"Humidity");
$myPicture->myData->setAxisXY(1,AXIS_Y);
$myPicture->myData->setAxisUnit(1,"%");
$myPicture->myData->setAxisPosition(1,AXIS_POSITION_RIGHT);

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
$myScatter->drawScatterScale();

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawScatterScale.png");

?>
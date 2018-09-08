<?php   
/* CAT:Scaling */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */ 
$myPicture->myData->addPoints([24,-25,26,25,25],"Temperature");
$myPicture->myData->addPoints([1,2,VOID,9,10],"Humidity 1");
$myPicture->myData->addPoints([1,VOID,7,-9,0],"Humidity 2");
$myPicture->myData->addPoints([-1,-1,-1,-1,-1],"Humidity 3");
$myPicture->myData->addPoints([0,0,0,0,0],"Vide");
$myPicture->myData->setSerieOnAxis("Temperature",0);
$myPicture->myData->setSerieOnAxis("Humidity 1",1);
$myPicture->myData->setSerieOnAxis("Humidity 2",1);
$myPicture->myData->setSerieOnAxis("Humidity 3",1);
$myPicture->myData->setSerieOnAxis("Vide",2);
$myPicture->myData->setAxisPosition(2,AXIS_POSITION_RIGHT);
$myPicture->myData->setAxisName(0,"Temperature");
$myPicture->myData->setAxisName(1,"Humidity");
$myPicture->myData->setAxisName(2,"Empty value");

/* Create the abscissa serie */
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun"],"Labels");
$myPicture->myData->setSerieDescription("Labels","My labels");
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
$myPicture->drawText(10,13,"drawScale() - draw the X-Y scales",["Color"=>new pColor(255,255,255)]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Draw the scale */
$myPicture->setGraphArea(90,60,660,190);
$myPicture->drawFilledRectangle(90,60,660,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["DrawYLines"=>[0],"Pos"=>SCALE_POS_LEFTRIGHT]);

/* Write the chart title */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(350,55,"My chart title",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawScale.multiple.png");

?>
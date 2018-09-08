<?php   
/* CAT:Scaling */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([1700,2500,7800,4500,3150],"Distance");
$myPicture->myData->setAxisName(0,"Maximum distance");
$myPicture->myData->setAxisUnit(0,"m");
$myPicture->myData->setAxisDisplay(0,AXIS_FORMAT_METRIC);

/* Create the abscissa serie */
$myPicture->myData->addPoints([1230768000,1233446400,1235865600,1238544000,1241136000,1243814400],"Timestamp");
$myPicture->myData->setSerieDescription("Timestamp","Sampled Dates");
$myPicture->myData->setAbscissa("Timestamp");
$myPicture->myData->setAbscissaName("Dates");
$myPicture->myData->setXAxisDisplay(AXIS_FORMAT_DATE);

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
$myPicture->setGraphArea(60,60,660,190);
$myPicture->drawScale();

/* Write the chart title */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(350,55,"My chart title",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
$myPicture->drawFilledRectangle(60,60,660,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawScale.dates.png");

?>
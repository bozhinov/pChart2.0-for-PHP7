<?php   
/* CAT:Scaling */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,390);

/* Populate the pData object */
$myPicture->myData->addPoints([8,10,24,25,25,24,23,22,20,12,10,4],"Temperature");
$myPicture->myData->addPoints([2,4,6,4,5,3,6,4,5,8,6,1],"Pressure");
$myPicture->myData->setSerieProperties("Pressure",["isDrawable" => FALSE]);
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,390,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,390,DIRECTION_VERTICAL, ["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,389,["Color"=>new pColor(0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"fonts/PressStart2P-Regular.ttf","FontSize"=>6]);
$myPicture->drawText(10,15,"drawScale() - draw the X-Y scales",["Color"=>new pColor(255)]);

/* Write the chart title */ 
$myPicture->setFontProperties(["FontName"=>"fonts/Cairo-Regular.ttf","FontSize"=>11]);
$myPicture->drawText(350,55,"My chart title",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Define the 1st chart area */
$myPicture->setGraphArea(60,70,660,200);
$myPicture->setFontProperties(["FontSize"=>7]);

/* Draw the scale */
$myPicture->drawScale(["DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE,"RemoveXAxis"=>TRUE]);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);
$pCharts->drawBarChart(["Surrounding"=>-30,"InnerSurrounding"=>30]);

/* Define the 2nd chart area */
$myPicture->setGraphArea(60,220,660,360);

/* Draw the scale */
$myPicture->myData->setSerieProperties("Temperature",["isDrawable" => FALSE]);
$myPicture->myData->setSerieProperties("Pressure",["isDrawable" => TRUE]);
$myPicture->myData->setAxisName(0,"Pressure");

$myPicture->drawScale(["DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);

$pCharts->drawBarChart(["Surrounding"=>-30,"InnerSurrounding"=>30]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawScale.labels.png");

?>
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
$myPicture->myData->setSerieDrawable("Pressure",FALSE);
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
$myPicture->drawRectangle(0,0,699,389,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawScale() - draw the X-Y scales",["Color"=>new pColor(255,255,255)]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(350,55,"My chart title",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Define the 1st chart area */
$myPicture->setGraphArea(60,70,660,200);
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw the scale */
$pCharts->myPicture->drawScale(["DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE,"RemoveXAxis"=>TRUE]);
$pCharts->drawBarChart(["Surrounding"=>-30,"InnerSurrounding"=>30]);

/* Define the 2nd chart area */
$pCharts->myPicture->setGraphArea(60,220,660,360);
$pCharts->myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Draw the scale */
$pCharts->myPicture->myData->setSerieDrawable("Temperature",FALSE);
$pCharts->myPicture->myData->setSerieDrawable("Pressure",TRUE);
$pCharts->myPicture->myData->setAxisName(0,"Pressure");
$pCharts->myPicture->drawScale(["DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);
$pCharts->drawBarChart(["Surrounding"=>-30,"InnerSurrounding"=>30]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawScale.labels.png");

?>
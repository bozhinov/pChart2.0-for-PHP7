<?php   
/* CAT:Bar Chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(500,220);

/* Populate the pData object */
$myPicture->myData->addPoints([60,30,10],"Answers");
$myPicture->myData->setAxisName(0,"Answers (%)");
$myPicture->myData->addPoints(["I do agree  ","I disagree  ","No opinion  "],"Options");
$myPicture->myData->setAbscissa("Options");

/* Write the chart title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>15]);
$myPicture->drawText(20,34,"Q: Flexibility is a key point of this library",["FontSize"=>20]);

/* Define the default font */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Set the graph area */ 
$myPicture->setGraphArea(70,60,480,200);
$myPicture->drawGradientArea(70,60,480,200,DIRECTION_HORIZONTAL,["StartColor"=>new pColor(200,200,200,30),"EndColor"=>new pColor(255,255,255,30)]);

/* Draw the chart scale */ 
$scaleSettings = array("AxisAlpha"=>10,"TickAlpha"=>10,"DrawXLines"=>FALSE,"Mode"=>SCALE_MODE_START0,"GridColor"=>new pColor(0,0,0,10),"Pos"=>SCALE_POS_TOPBOTTOM);
$myPicture->drawScale($scaleSettings);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw the chart */ 
(new pCharts($myPicture))->drawBarChart(["DisplayValues"=>TRUE,"DisplayShadow"=>TRUE,"DisplayPos"=>LABEL_POS_INSIDE,"Rounded"=>TRUE,"Surrounding"=>30]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawBarChart.poll.png");

?>
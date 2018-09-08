<?php   
/* CAT:Pie charts */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pPie;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */ 
$myPicture->myData->addPoints([40,60,15,10,6,4],"ScoreA");
$myPicture->myData->setSerieDescription("ScoreA","Application A");

/* Define the abscissa serie */
$myPicture->myData->addPoints(["<10","10<>20","20<>40","40<>60","60<>80",">80"],"Labels");
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
$myPicture->drawText(10,13,"drawPieLegend - Draw pie charts legend",["Color"=>new pColor(255,255,255)]);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"Color"=>new pColor(80,80,80)));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(150,150,150,100)]);

/* Create the pPie object */ 
$PieChart = new pPie($myPicture);

/* Draw two AA pie chart */ 
$PieChart->draw2DPie(200,100,["Border"=>TRUE]);
$PieChart->draw2DPie(440,115,["Border"=>TRUE]);

/* Write down the legend next to the 2nd chart*/
$PieChart->drawPieLegend(550,70);

/* Write a legend box under the 1st chart */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
$PieChart->drawPieLegend(90,176,["Style"=>LEGEND_BOX,"Mode"=>LEGEND_HORIZONTAL]);

/* Write the bottom legend box */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6,"Color"=>new pColor(0,0,0,60)));
 
$myPicture->drawGradientArea(1,200,698,228,DIRECTION_VERTICAL,["StartColor"=>new pColor(247,247,247,20), "EndColor"=>new pColor(217,217,217,20)]);
$myPicture->drawLine(1,199,698,199,["Color"=>new pColor(100,100,100,20)]);
$myPicture->drawLine(1,200,698,200,["Color"=>new pColor(255,255,255,20)]);
$PieChart->drawPieLegend(10,210,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawPieLegend.png");

?>
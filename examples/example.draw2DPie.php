<?php   
/* CAT:Pie charts */

/* pChart library inclusions */
require_once("bootstrap.php");
require_once("functions.inc.php");

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

/* Draw a solid background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(173,152,217), "Dash"=>TRUE, "DashColor"=>new pColor(193,172,237)]);

/* Draw a gradient overlay */
$myPicture->drawGradientArea(0,0,700,230, DIRECTION_VERTICAL, ["StartColor"=>new pColor(209,150,231,50), "EndColor"=>new pColor(111,3,138,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL,  ["StartColor"=>ColorBlack(),"EndColor"=>new pColor(50,50,50,100)]);
/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>ColorBlack()]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pPie - Draw 2D pie charts",["Color"=>ColorWhite()]);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"Color"=>new pColor(80,80,80)));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>ColorBlack($Alpha=50)]);

/* Create the pPie object */ 
$PieChart = new pPie($myPicture);

/* Draw a simple pie chart */ 
$PieChart->draw2DPie(120,125,["SecondPass"=>FALSE]);

/* Draw an AA pie chart */ 
$PieChart->draw2DPie(340,125,["DrawLabels"=>TRUE,"LabelStacked"=>TRUE,"Border"=>TRUE]);

/* Draw a split pie chart */ 
$PieChart->draw2DPie(560,125,["WriteValues"=>PIE_VALUE_PERCENTAGE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE,"BorderColor"=>ColorWhite()]);

/* Write the legend */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>ColorBlack($Alpha=20)]);
$myPicture->drawText(120,200,"Single AA pass",["DrawBox"=>TRUE,"BoxRounded"=>TRUE,"Color"=>ColorBlack(),"Align"=>TEXT_ALIGN_TOPMIDDLE]);
$myPicture->drawText(440,200,"Extended AA pass / Split",["DrawBox"=>TRUE,"BoxRounded"=>TRUE,"Color"=>ColorBlack(),"Align"=>TEXT_ALIGN_TOPMIDDLE,"BoxBorderColor"=>new pColor(0,0,0)]);

/* Write the legend box */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6,"Color"=>ColorWhite()]);
$PieChart->drawPieLegend(380,8,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.draw2DPie.png");

?>
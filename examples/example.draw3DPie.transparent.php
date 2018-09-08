<?php   
/* CAT:Pie charts */

/* pChart library inclusions */
require_once("bootstrap.php");
require_once("functions.inc.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pPie;

/* Create the pChart object */
$myPicture = new pDraw(240,180,TRUE);

/* Populate the pData object */
$myPicture->myData->addPoints([40,30,20],"ScoreA");
$myPicture->myData->setSerieDescription("ScoreA","Application A");

/* Define the abscissa serie */
$myPicture->myData->addPoints(["A","B","C"],"Labels");
$myPicture->myData->setAbscissa("Labels");

/* Set the default font properties */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"Color"=>new pColor(80,80,80)]);

/* Create the pPie object */ 
$PieChart = new pPie($myPicture);

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>3,"Y"=>3,"Color"=>ColorBlack($Alpha=10)]);

/* Draw a split pie chart */ 
$PieChart->draw3DPie(120,90,["Radius"=>100,"DataGapAngle"=>12,"DataGapRadius"=>10,"Border"=>TRUE]);

/* Write the legend box */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6,"Color"=>ColorBlack()]);
$PieChart->drawPieLegend(140,160,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.draw3DPie.transparent.png");

?>
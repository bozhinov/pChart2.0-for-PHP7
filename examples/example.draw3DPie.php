<?php   
/* CAT:Pie charts */

/* pChart library inclusions */
require_once("bootstrap.php");
require_once("functions.inc.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pPie;

/* Create the pChart object */
$myPicture = new pDraw(700,230,TRUE);

/* Populate the pData object */ 
$myPicture->myData->addPoints([40,30,20],"ScoreA");
$myPicture->myData->setSerieDescription("ScoreA","Application A");

/* Define the abscissa serie */
$myPicture->myData->addPoints(["A","B","C"],"Labels");
$myPicture->myData->setAbscissa("Labels");

/* Draw a solid background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(173,152,217), "Dash"=>TRUE, "DashColor"=>new pColor(193,172,237)]);

/* Draw a gradient overlay */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(209,150,231,50), "EndColor"=>new pColor(111,3,138,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL,["StartColor"=>ColorBlack(), "EndColor"=>new pColor(50,50,50,100)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>ColorBlack()]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"pPie - Draw 3D pie charts",["Color"=>ColorWhite()]);

/* Set the default font properties */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"Color"=>new pColor(80,80,80)]);

/* Create the pPie object */ 
$PieChart = new pPie($myPicture);

/* Define the slice color */
$PieChart->setSliceColor(0,new pColor(143,197,0));
$PieChart->setSliceColor(1,new pColor(97,77,63));
$PieChart->setSliceColor(2,new pColor(97,113,63));

/* Draw a simple pie chart */ 
$PieChart->draw3DPie(120,125,["SecondPass"=>FALSE]);

/* Draw an AA pie chart */ 
$PieChart->draw3DPie(340,125,["DrawLabels"=>TRUE,"Border"=>TRUE]);

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>3,"Y"=>3,"Color"=>ColorBlack($Alpha=10)]);

/* Draw a split pie chart */ 
$PieChart->draw3DPie(560,125,["WriteValues"=>TRUE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE]);

/* Write the legend */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>ColorBlack($Alpha=20)]);
$myPicture->drawText(120,200,"Single AA pass",["DrawBox"=>TRUE,"BoxRounded"=>TRUE,"Color"=>ColorBlack(),"Align"=>TEXT_ALIGN_TOPMIDDLE]);
$myPicture->drawText(440,200,"Extended AA pass / Split",["DrawBox"=>TRUE,"BoxRounded"=>TRUE,"Color"=>ColorBlack(),"Align"=>TEXT_ALIGN_TOPMIDDLE]);

/* Write the legend box */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6,"Color"=>ColorWhite()]);
$PieChart->drawPieLegend(600,8,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.draw3DPie.png");

?>
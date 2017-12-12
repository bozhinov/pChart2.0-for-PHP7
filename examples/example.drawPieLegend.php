<?php   
/* CAT:Pie charts */

/* pChart library inclusions */
require_once("bootstrap.php");

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
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,["StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawPieLegend - Draw pie charts legend",["R"=>255,"G"=>255,"B"=>255]);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"R"=>150,"G"=>150,"B"=>150,"Alpha"=>100]);

/* Create the pPie object */ 
$PieChart = new pPie($myPicture);

/* Draw two AA pie chart */ 
$PieChart->draw2DPie(200,100,["Border"=>TRUE]);
$PieChart->draw2DPie(440,115,["Border"=>TRUE]);

/* Write down the legend next to the 2nd chart*/
$PieChart->drawPieLegend(550,70);

/* Write a legend box under the 1st chart */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
$PieChart->drawPieLegend(90,176,array("Style"=>LEGEND_BOX,"Mode"=>LEGEND_HORIZONTAL));

/* Write the bottom legend box */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawGradientArea(1,200,698,228,DIRECTION_VERTICAL,["StartR"=>247,"StartG"=>247,"StartB"=>247,"EndR"=>217,"EndG"=>217,"EndB"=>217,"Alpha"=>20]);
$myPicture->drawLine(1,199,698,199,["R"=>100,"G"=>100,"B"=>100,"Alpha"=>20]);
$myPicture->drawLine(1,200,698,200,["R"=>255,"G"=>255,"B"=>255,"Alpha"=>20]);
$PieChart->drawPieLegend(10,210,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawPieLegend.png");

?>
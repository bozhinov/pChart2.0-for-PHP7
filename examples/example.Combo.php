<?php
/* CAT:Combo */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

$myPicture->myData->addPoints([30,24,32],"This year");
$myPicture->myData->addPoints([28,20,27],"Last year");
$myPicture->myData->setSerieTicks("Last year",4);
$myPicture->myData->addPoints(["Year","Month","Day"],"Labels");
$myPicture->myData->setAbscissa("Labels");

/* Turn on antialiasing */
$myPicture->Antialias = FALSE;

/* Create a solid background */
$Settings = array("R"=>179, "G"=>217, "B"=>91, "Dash"=>1, "DashR"=>199, "DashG"=>237, "DashB"=>111);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

/* Do a gradient overlay */
$Settings = array("StartR"=>194, "StartG"=>231, "StartB"=>44, "EndR"=>43, "EndG"=>107, "EndB"=>58, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,["StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"Chart title",["R"=>255,"G"=>255,"B"=>255]);

/* Draw the scale */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
$myPicture->setGraphArea(50,60,670,190);
$myPicture->drawFilledRectangle(50,60,670,190,["R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10]);
$myPicture->drawScale(["CycleBackground"=>TRUE]);

/* Graph title */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);
$myPicture->drawText(50,52,"Chart subtitle",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMLEFT]);

/* Create the pMist object */
$pCharts = new pCharts($myPicture);

/* Draw the bar chart chart */
$pCharts->myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
$pCharts->myPicture->myData->setSerieDrawable("Last year",FALSE);
$pCharts->drawBarChart();

/* Turn on antialiasing */
$myPicture->Antialias = TRUE;

/* Draw the line and plot chart */
$pCharts->myPicture->myData->setSerieDrawable("Last year",TRUE);
$pCharts->myPicture->myData->setSerieDrawable("This year",FALSE);
$pCharts->myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);
$pCharts->drawSplineChart();

$myPicture->setShadow(FALSE);
$pCharts->drawPlotChart(["PlotSize"=>3,"PlotBorder"=>TRUE,"BorderSize"=>3,"BorderAlpha"=>20]);

/* Make sure all series are drawable before writing the scale */
$myPicture->myData->setAllDrawable(); # was drawAll

/* Write the legend */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);
$myPicture->drawLegend(580,35,["Style"=>LEGEND_ROUND,"Alpha"=>20,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.combo.png");

?>
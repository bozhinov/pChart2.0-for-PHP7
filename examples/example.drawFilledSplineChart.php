<?php   
/* CAT:Spline chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->setAxisName(0,"Strength");
for($i=0;$i<=720;$i=$i+20)
{
	$myPicture->myData->addPoints(cos(deg2rad($i))*100,"Probe 1");
	$myPicture->myData->addPoints(cos(deg2rad($i+90))*60,"Probe 2");
}

$myPicture->drawGradientArea(0,0,700,304,DIRECTION_VERTICAL,["StartR"=>47,"StartG"=>47,"StartB"=>47,"EndR"=>17,"EndG"=>17,"EndB"=>17,"Alpha"=>100]);
$myPicture->drawGradientArea(0,230,700,304,DIRECTION_VERTICAL,["StartR"=>47,"StartG"=>47,"StartB"=>47,"EndR"=>27,"EndG"=>27,"EndB"=>27,"Alpha"=>100]);
$myPicture->drawLine(0,209,847,209,["R"=>0,"G"=>0,"B"=>0]);
$myPicture->drawLine(0,210,847,210,["R"=>70,"G"=>70,"B"=>70]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,846,303,["R"=>204,"G"=>204,"B"=>204]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
$myPicture->drawText(340,12,"Cyclic magnetic field strength",["R"=>255,"G"=>255,"B"=>255,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE]);

/* Define the chart area */
$myPicture->setGraphArea(48,17,680,190);

/* Draw a rectangle */
$myPicture->drawFilledRectangle(53,22,675,185,["R"=>0,"G"=>0,"B"=>0,"Dash"=>TRUE,"DashR"=>0,"DashG"=>51,"DashB"=>51,"BorderR"=>0,"BorderG"=>0,"BorderB"=>0]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20]);

/* Draw the scale */
$myPicture->setFontProperties(["R"=>255,"G"=>255,"B"=>255]);
$ScaleSettings = array("XMargin"=>5,"YMargin"=>5,"Floating"=>TRUE,"DrawSubTicks"=>TRUE,"GridR"=>255,"GridG"=>255,"GridB"=>255,"AxisR"=>255,"AxisG"=>255,"AxisB"=>255,"GridAlpha"=>30,"CycleBackground"=>TRUE);
$myPicture->drawScale($ScaleSettings);

/* Draw the spline chart */
(new pCharts($myPicture))->drawFilledSplineChart();

/* Write the chart boundaries */
$BoundsSettings = array("MaxDisplayR"=>237,"MaxDisplayG"=>23,"MaxDisplayB"=>48,"MinDisplayR"=>23,"MinDisplayG"=>144,"MinDisplayB"=>237);
$myPicture->writeBounds(BOUND_BOTH,$BoundsSettings);

/* Write the 0 line */
$myPicture->drawThreshold(0,["WriteCaption"=>TRUE]);

/* Write the chart legend */
$myPicture->setFontProperties(["R"=>255,"G"=>255,"B"=>255]);
$myPicture->drawLegend(580,217,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Write the 1st data series statistics */
$Settings = array("R"=>188,"G"=>224,"B"=>46,"Align"=>TEXT_ALIGN_BOTTOMLEFT);
$myPicture->drawText(10,222,"Max : ".ceil($myPicture->myData->getMax("Probe 1")),$Settings);
$myPicture->drawText(60,222,"Min : ".ceil($myPicture->myData->getMin("Probe 1")),$Settings);
$myPicture->drawText(110,222,"Avg : ".ceil($myPicture->myData->getSerieAverage("Probe 1")),$Settings);

/* Write the 2nd data series statistics */
$Settings = array("R"=>224,"G"=>100,"B"=>46,"Align"=>TEXT_ALIGN_BOTTOMLEFT);
$myPicture->drawText(160,222,"Max : ".ceil($myPicture->myData->getMax("Probe 2")),$Settings);
$myPicture->drawText(210,222,"Min : ".ceil($myPicture->myData->getMin("Probe 2")),$Settings);
$myPicture->drawText(260,222,"Avg : ".ceil($myPicture->myData->getSerieAverage("Probe 2")),$Settings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawFilledSplineChart.png");

?>
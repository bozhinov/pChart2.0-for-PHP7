<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([2,7,5,18,19,22,23,25,22,12,10,10],"DEFCA");
$myPicture->myData->setAxisName(0,"$ Incomes");
$myPicture->myData->setAxisDisplay(0,AXIS_FORMAT_CURRENCY);
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");
$myPicture->myData->setPalette("DEFCA",new pColor(55,91,127));


$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(220,220,220,100), "EndColor"=>new pColor(255,255,255,100)]);
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(220,220,220)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);
$myPicture->drawText(60,35,"2k9 Average Incomes",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMLEFT]);

/* Do some cosmetic and draw the chart */
$myPicture->setGraphArea(60,40,670,190);
$myPicture->drawFilledRectangle(60,40,670,190,["Color"=>new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["GridColor"=>new pColor(180,180,180,50)]);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw a spline chart on top */
$pCharts->myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$pCharts->drawFilledSplineChart();

$pCharts->myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,10)]);
$pCharts->drawSplineChart();
$myPicture->setShadow(FALSE);

/* Write the chart legend */ 
$myPicture->drawLegend(643,210,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawSimple.png");

?>
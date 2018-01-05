<?php   
/* CAT:Mathematical */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([3,12,15,8,5,-5,5,-5,-3,4,5,10],"Probe");
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");
$myPicture->myData->setAbscissaName("Months");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Set the default font */ 
$myPicture->setFontProperties(["Color"=>new pColor(0,0,0),"FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);

/* Define the chart area */
$myPicture->setGraphArea(50,40,680,170);

/* Draw the scale */
$myPicture->drawScale(["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"DrawSubTicks"=>TRUE,"GridColor"=>new pColor(100,100,100,15)]);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw the chart */
$pCharts->myPicture->Antialias = TRUE;
$pCharts->drawSplineChart();
$pCharts->myPicture->Antialias = FALSE;

/* Draw the series derivative graph */
$pCharts->drawDerivative(["Caption"=>FALSE]);

/* Write the chart legend */
$myPicture->drawLegend(640,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawDerivative.simple.png");

?>
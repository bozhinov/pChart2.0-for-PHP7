<?php   
/* CAT:Mathematical */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints(array(3,12,15,8,5,-5,5,-5,-3,4,5,10),"Probe");
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints(array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"),"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");
$myPicture->myData->setAbscissaName("Months");

/* Turn of AAliasing */
$myPicture->Antialias = FALSE;

/* Set the default font */ 
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(50,40,680,170);

/* Draw the scale */
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"DrawSubTicks"=>TRUE,"GridR"=>100,"GridG"=>100,"GridB"=>100,"GridAlpha"=>15);
$myPicture->drawScale($scaleSettings);

/* Create the pMist object */
$pCharts = new pCharts($myPicture);

/* Draw the chart */
$pCharts->myPicture->Antialias = TRUE;
$pCharts->drawSplineChart();
$myPicture->Antialias = FALSE; # Momchil: requried ?

/* Draw the series derivative graph */
$myPicture->drawDerivative(array("Caption"=>FALSE));

/* Write the chart legend */
$myPicture->drawLegend(640,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawDerivative.simple.png");

?>
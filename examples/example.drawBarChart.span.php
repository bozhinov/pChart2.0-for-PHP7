<?php   
/* CAT:Bar Chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->loadPalette("pChart/palettes/blind.color",TRUE);
$myPicture->myData->addPoints(array(150,220,300,250,420,200,300,200,110),"Server A");
$myPicture->myData->addPoints(array("January","February","March","April","May","Juin","July","August","September"),"Months");
$myPicture->myData->setSerieDescription("Months","Month");
$myPicture->myData->setAbscissa("Months");

/* Create the floating 0 data serie */
$myPicture->myData->addPoints(array(60,80,20,40,40,50,90,30,100),"Floating 0");
$myPicture->myData->setSerieDrawable("Floating 0",FALSE);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"R"=>110,"G"=>110,"B"=>110));

/* Write the title */
$myPicture->drawText(10,13,"Net Income 2k8");

/* Set the graphical area  */
$myPicture->setGraphArea(50,30,680,180);

/* Draw the scale  */
$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>500));
$myPicture->drawScale(array("InnerTickWidth"=>0,"OuterTickWidth"=>0,"Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"LabelRotation"=>45,"DrawXLines"=>FALSE,"GridR"=>0,"GridG"=>0,"GridB"=>0,"GridTicks"=>0,"GridAlpha"=>30,"AxisAlpha"=>0));

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

/* Draw the chart */
$settings = array("Floating0Serie"=>"Floating 0","Surrounding"=>10);
(new pCharts($myPicture))->drawBarChart($settings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawBarChart.span.png");

?>
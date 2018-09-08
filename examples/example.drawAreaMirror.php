<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");
require_once("myColors.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([150,220,300,250,420,200,300,200,100],"Server A");
$myPicture->myData->addPoints([140,0,340,300,320,300,200,100,50],"Server B");
$myPicture->myData->setAxisName(0,"Hits");
$myPicture->myData->addPoints(["January","February","March","April","May","June","July","August","September"],"Months");
$myPicture->myData->setSerieDescription("Months","Month");
$myPicture->myData->setAbscissa("Months");
$myPicture->myData->setAbsicssaPosition(AXIS_POSITION_TOP);

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Add a border to the picture */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL, myColors::myGridColor());
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,myColors::myGridColor(20));
$myPicture->drawRectangle(0,0,699,229,["Color"=>myColors::Black()]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$myPicture->drawScale(["GridColor"=>myColors::LightGray(),"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);

/* Write the chart legend */
$myPicture->drawLegend(580,12,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>myColors::Black(10)]);

/* Draw the chart */
(new pCharts($myPicture))->drawBarChart(["Surrounding"=>-30,"InnerSurrounding"=>30,"Interleave"=>0]);

/* Draw the bottom black area */
$myPicture->setShadow(FALSE);
$myPicture->drawFilledRectangle(0,174,700,230,["Color"=>myColors::Black()]);

/* Do the mirror effect */
$myPicture->drawAreaMirror(0,174,700,48);

/* Draw the horizon line */
$myPicture->drawLine(1,174,698,174,["Color"=>myColors::Gray()]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawAreaMirror.png");

?>
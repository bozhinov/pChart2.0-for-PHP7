<?php   
/* CAT:Mathematical */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pScatter;

/* Create the pChart object */
$myPicture = new pDraw(800,582);

/* Define all the data series */
$myPicture->myData->addPoints([10,8,13,9,11,14,6,4,12,7,5],"X1");
$myPicture->myData->addPoints([8.04,6.95,7.58,8.81,8.33,9.96,7.24,4.26,10.84,4.82,5.68],"Y1");
$myPicture->myData->addPoints([10,8,13,9,11,14,6,4,12,7,5],"X2");
$myPicture->myData->addPoints([9.14,8.14,8.74,8.77,9.26,8.1,6.13,3.1,9.13,7.26,4.74],"Y2");
$myPicture->myData->addPoints([10,8,13,9,11,14,6,4,12,7,5],"X3");
$myPicture->myData->addPoints([7.46,6.77,12.74,7.11,7.81,8.84,6.08,5.39,8.15,6.42,5.73],"Y3");
$myPicture->myData->addPoints([8,8,8,8,8,8,8,19,8,8,8],"X4");
$myPicture->myData->addPoints([6.58,5.76,7.71,8.84,8.47,7.04,5.25,12.5,5.56,7.91,6.89],"Y4");

/* Create the X axis */
$myPicture->myData->setAxisName(0,"X");
$myPicture->myData->setAxisXY(0,AXIS_X);
$myPicture->myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

/* Create the Y axis */
$myPicture->myData->setSerieOnAxis("Y1",1);
$myPicture->myData->setSerieOnAxis("Y2",1);
$myPicture->myData->setSerieOnAxis("Y3",1);
$myPicture->myData->setSerieOnAxis("Y4",1);
$myPicture->myData->setAxisName(1,"Y");
$myPicture->myData->setAxisXY(1,AXIS_Y);
$myPicture->myData->setAxisPosition(1,AXIS_POSITION_LEFT);

/* Create the scatter chart binding */
$myPicture->myData->setScatterSerie("X1","Y1",0);
$myPicture->myData->setScatterSerie("X2","Y2",1);
$myPicture->myData->setScatterSerie("X3","Y3",2);
$myPicture->myData->setScatterSerie("X4","Y4",3);
$myPicture->myData->setScatterSerieDrawable(1,FALSE);
$myPicture->myData->setScatterSerieDrawable(2,FALSE);
$myPicture->myData->setScatterSerieDrawable(3,FALSE);

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,800,582,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,800,582,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,799,581,["Color"=>new pColor(0,0,0)]);

/* Write the title */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>23]);
$myPicture->drawText(55,50,"Anscombe's Quartet drawing example",["Color"=>new pColor(255,255,255)]);
$myPicture->drawText(55,65,"This example demonstrate the importance of graphing data before analyzing it. (The line of best fit is the same for all datasets)",["FontSize"=>12,"Color"=>new pColor(255,255,255)]);

/* Set the default font */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture);

/* Turn on shadow computing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw the 1st chart */
$myPicture->setGraphArea(56,90,380,285);
$myScatter->drawScatterScale(["XMargin"=>5,"YMargin"=>5,"Floating"=>TRUE,"DrawSubTicks"=>TRUE]);
$myScatter->drawScatterPlotChart();
$myScatter->drawScatterBestFit();

/* Draw the 2nt chart */
$myScatter->myPicture->myData->setScatterSerieDrawable(0,FALSE);
$myScatter->myPicture->myData->setScatterSerieDrawable(1,TRUE);
$myScatter->myPicture->setGraphArea(436,90,760,285);
$myScatter->drawScatterScale(["XMargin"=>5,"YMargin"=>5,"Floating"=>TRUE,"DrawSubTicks"=>TRUE]);
$myScatter->drawScatterPlotChart();
$myScatter->drawScatterBestFit();

/* Draw the 3rd chart */
$myScatter->myPicture->myData->setScatterSerieDrawable(1,FALSE);
$myScatter->myPicture->myData->setScatterSerieDrawable(2,TRUE);
$myScatter->myPicture->setGraphArea(56,342,380,535);
$myScatter->drawScatterScale(["XMargin"=>5,"YMargin"=>5,"Floating"=>TRUE,"DrawSubTicks"=>TRUE]);
$myScatter->drawScatterPlotChart();
$myScatter->drawScatterBestFit();

/* Draw the 4th chart */
$myScatter->myPicture->myData->setScatterSerieDrawable(2,FALSE);
$myScatter->myPicture->myData->setScatterSerieDrawable(3,TRUE);
$myScatter->myPicture->setGraphArea(436,342,760,535);
$myScatter->drawScatterScale(["XMargin"=>5,"YMargin"=>5,"Floating"=>TRUE,"DrawSubTicks"=>TRUE]);
$myScatter->drawScatterPlotChart();
$myScatter->drawScatterBestFit();

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawAnscombeQuartet.png");

?>
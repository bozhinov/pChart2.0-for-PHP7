<?php   
/* CAT:Stacked chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([-7,-8,-15,-20,-18,-12,8,-19,9,16,-20,8,10,-10,-14,-20,8,-9,-19],"Probe 3");
$myPicture->myData->addPoints([19,0,-8,8,-8,12,-19,-10,5,12,-20,-8,10,-11,-12,8,-17,-14,0],"Probe 4");
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints([4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22],"Time");
$myPicture->myData->setSerieDescription("Time","Hour of the day");
$myPicture->myData->setAbscissa("Time");
$myPicture->myData->setXAxisUnit("h");

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);

/* Set the default font properties */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Draw the scale */
$myPicture->setGraphArea(60,30,650,190);
$myPicture->drawScale(array("CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"GridR"=>0,"GridG"=>0,"GridB"=>0,"GridAlpha"=>10,"Mode"=>SCALE_MODE_ADDALL));

/* Turn on shadow computing */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);

/* Draw some thresholds */
$myPicture->setShadow(FALSE);
$myPicture->drawThreshold([-40],["WriteCaption"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Ticks"=>4]);
$myPicture->drawThreshold([28],["WriteCaption"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Ticks"=>4]);

/* Draw the chart */
(new pCharts($myPicture))->drawStackedBarChart(["Rounded"=>TRUE,"DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO,"DisplaySize"=>6,"BorderR"=>255,"BorderG"=>255,"BorderB"=>255]);

/* Write the chart legend */
$myPicture->drawLegend(570,212,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawStackedBarChart.rounded.png");

?>
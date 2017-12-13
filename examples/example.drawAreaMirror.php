<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints(array(150,220,300,250,420,200,300,200,100),"Server A");
$myPicture->myData->addPoints(array(140,0,340,300,320,300,200,100,50),"Server B");
$myPicture->myData->setAxisName(0,"Hits");
$myPicture->myData->addPoints(array("January","February","March","April","May","June","July","August","September"),"Months");
$myPicture->myData->setSerieDescription("Months","Month");
$myPicture->myData->setAbscissa("Months");
$myPicture->myData->setAbsicssaPosition(AXIS_POSITION_TOP); 

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Add a border to the picture */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>80,"EndG"=>80,"EndB"=>80,"Alpha"=>100));
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>80,"EndG"=>80,"EndB"=>80,"Alpha"=>20));
$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$scaleSettings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Write the chart legend */
$myPicture->drawLegend(580,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

/* Draw the chart */
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
$settings = array("Surrounding"=>-30,"InnerSurrounding"=>30,"Interleave"=>0);

(new pCharts($myPicture))->drawBarChart($settings);

/* Draw the bottom black area */
$myPicture->setShadow(FALSE);
$myPicture->drawFilledRectangle(0,174,700,230,["R"=>0,"G"=>0,"B"=>0]);

/* Do the mirror effect */
$myPicture->drawAreaMirror(0,174,700,48);

/* Draw the horizon line */
$myPicture->drawLine(1,174,698,174,["R"=>80,"G"=>80,"B"=>80]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawAreaMirror.png");

?>
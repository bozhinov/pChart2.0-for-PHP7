<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object with some random values*/
$myPicture->myData->addRandomValues("Probe 1",["Values"=>30,"Min"=>0,"Max"=>4]);
$myPicture->myData->addRandomValues("Probe 2",["Values"=>30,"Min"=>6,"Max"=>10]);
$myPicture->myData->addRandomValues("Probe 3",["Values"=>30,"Min"=>12,"Max"=>16]);
$myPicture->myData->addRandomValues("Probe 4",["Values"=>30,"Min"=>18,"Max"=>22]);
$myPicture->myData->setAxisName(0,"Probes");

/* Create a solid background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=> new pColor(179,217,91), "Dash"=>1, "DashColor"=> new pColor(199,237,111)]);

/* Do a gradient overlay */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(194,231,44,50),"EndColor"=>new pColor(43,107,58,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,100),"EndColor"=>new pColor(50,50,50,100)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color" => new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"addRandomValues() :: assess your scales",["Color" => new pColor(255,255,255)]);

/* Draw the scale */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->setGraphArea(50,60,670,190);
$myPicture->drawFilledRectangle(50,60,670,190,["Color"=> new pColor(255,255,255,10),"Surrounding"=>-200]);
$myPicture->drawScale(["CycleBackground"=>TRUE,"LabelSkip"=>4,"DrawSubTicks"=>TRUE]);

/* Graph title */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=> new pColor(0,0,0,10)]);
$myPicture->drawText(50,52,"Magnetic noise",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMLEFT]);

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw the data series */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$pCharts->drawSplineChart();
$myPicture->setShadow(FALSE);

/* Write the legend */
$myPicture->drawLegend(475,50,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.addRandomValues.png");

?>
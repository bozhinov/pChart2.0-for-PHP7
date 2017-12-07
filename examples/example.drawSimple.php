<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([2,7,5,18,19,22,23,25,22,12,10,10],"DEFCA");
$myPicture->myData->setAxisName(0,"$ Incomes");
$myPicture->myData->setAxisDisplay(0,AXIS_FORMAT_CURRENCY);
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun","Jul","Aou","Sep","Oct","Nov","Dec"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");
$myPicture->myData->setPalette("DEFCA",["R"=>55,"G"=>91,"B"=>127]);

$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>255,"EndG"=>255,"EndB"=>255,"Alpha"=>100]);
$myPicture->drawRectangle(0,0,699,229,["R"=>200,"G"=>200,"B"=>200]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);
$myPicture->drawText(60,35,"2k9 Average Incomes",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMLEFT]);

/* Do some cosmetic and draw the chart */
$myPicture->setGraphArea(60,40,670,190);
$myPicture->drawFilledRectangle(60,40,670,190,["R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10]);
$myPicture->drawScale(["GridR"=>180,"GridG"=>180,"GridB"=>180]);

/* Create the pMist object */
$pCharts = new pCharts($myPicture);

/* Draw a spline chart on top */
$pCharts->myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$pCharts->drawFilledSplineChart();

$pCharts->myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);
$pCharts->drawSplineChart();
$myPicture->setShadow(FALSE);

/* Write the chart legend */ 
$myPicture->drawLegend(643,210,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawSimple.png");

?>
<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([2,7,5,18,VOID,12,10,15,8,5,6,9],"Help Desk");
$myPicture->myData->setAxisName(0,"Incidents");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartR"=>100,"StartG"=>100,"StartB"=>100,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100]);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,["StartR"=>100,"StartG"=>100,"StartB"=>100,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>20]);
$myPicture->drawGradientArea(0,0,60,230,DIRECTION_HORIZONTAL,["StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100]);

/* Do some cosmetics */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);
$myPicture->drawLine(60,0,60,230,["R"=>70,"G"=>70,"B"=>70]);
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);
$myPicture->drawText(35,115,"Recorded cases",["R"=>255,"G"=>255,"B"=>255,"FontSize"=>20,"Angle"=>90,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Draw a spline chart */
$myPicture->setGraphArea(100,30,680,190);
$myPicture->drawFilledRectangle(100,30,680,190,["R"=>255,"G"=>255,"B"=>255,"Alpha"=>20]);
$myPicture->setFontProperties(["R"=>255,"G"=>255,"B"=>255,"FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$myPicture->drawScale(["AxisR"=>255,"AxisG"=>255,"AxisB"=>255,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);
(new pCharts($myPicture))->drawSplineChart();

/* Write the data bounds */
$myPicture->writeBounds();
$myPicture->setShadow(FALSE);

/* Write the chart legend */ 
$myPicture->drawLegend(630,215,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.writeBounds.png");

?>
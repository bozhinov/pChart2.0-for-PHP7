<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$Points_1 = [];
$Points_2 = [];
$Points_3 = [];
$Points_4 = [];
for($i=0; $i<=10;$i=$i+.2)
{ 
	$Points_1[] = log($i+1)*10;
	$Points_2[] = log($i+3)*10+rand(0,2)-1;
	$Points_3[] = log($i+6)*10;
	$Points_4[] = $i*10;
}
$myPicture->myData->addPoints($Points_1,"Bounds 1");
$myPicture->myData->addPoints($Points_2,"Probe 1");
$myPicture->myData->addPoints($Points_3,"Bounds 2");
$myPicture->myData->addPoints($Points_4,"Labels");

$myPicture->myData->setAxisName(0,"Size (cm)");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");
$myPicture->myData->setAbscissaName("Time (years)");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background and the border  */
$myPicture->drawFilledRectangle(0,0,699,229,["Color"=>new pColor(200,200,200)]);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(220,220,220,30), "EndColor"=>new pColor(100,100,100,30)]);
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(150,35,"Size by time generations",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(40,40,680,200);

/* Draw the scale */
$myPicture->drawScale(["LabelSkip"=>4,"XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridColor"=>new pColor(140,140,140,30),"DrawSubTicks"=>TRUE]);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw the line chart */
$pCharts->drawZoneChart("Bounds 1","Bounds 2",["LineAlpha"=>100,"AreaColor"=>new pColor(230,230,230,20),"LineTicks"=>3]);
$pCharts->myPicture->myData->setSerieDrawable("Bounds 1",FALSE);
$pCharts->myPicture->myData->setSerieDrawable("Bounds 2",FALSE);

/* Draw the line chart */
$pCharts->drawLineChart();
$pCharts->drawPlotChart(["PlotBorder"=>TRUE,"PlotSize"=>2,"BorderSize"=>3,"Surrounding"=>60,"BorderColor"=>new pColor(50,50,50,50)]);

/* Write the chart legend */
$myPicture->drawLegend(640,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawZoneChart.png");

?>
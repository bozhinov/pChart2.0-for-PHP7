<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
for($i=0; $i<=10;$i=$i+.2)
{
	$myPicture->myData->addPoints(log($i+1)*10,"Bounds 1");
	$myPicture->myData->addPoints(log($i+3)*10+rand(0,2)-1,"Probe 1");
	$myPicture->myData->addPoints(log($i+6)*10,"Bounds 2");
	$myPicture->myData->addPoints($i*10,"Labels");
}
$myPicture->myData->setAxisName(0,"Size (cm)");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");
$myPicture->myData->setAbscissaName("Time (years)");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background and the border  */
$myPicture->drawFilledRectangle(0,0,699,229,["R"=>200,"G"=>200,"B"=>200]);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>100,"EndG"=>100,"EndB"=>100,"Alpha"=>30]);
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(150,35,"Size by time generations",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(40,40,680,200);

/* Draw the scale */
$scaleSettings = array("LabelSkip"=>4,"XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridAlpha"=>30,"GridR"=>140,"GridG"=>140,"GridB"=>140,"DrawSubTicks"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Create the pCharts object */
$pCharts = new pCharts($myPicture);

/* Draw the line chart */
$pCharts->drawZoneChart("Bounds 1","Bounds 2",["LineAlpha"=>100,"AreaR"=>230,"AreaG"=>230,"AreaB"=>230,"AreaAlpha"=>20,"LineTicks"=>3]);
$pCharts->myPicture->myData->setSerieDrawable("Bounds 1",FALSE);
$pCharts->myPicture->myData->setSerieDrawable("Bounds 2",FALSE);

/* Draw the line chart */
$pCharts->drawLineChart();
$pCharts->drawPlotChart(["PlotBorder"=>TRUE,"PlotSize"=>2,"BorderSize"=>3,"Surrounding"=>60,"BorderAlpha"=>50]);

/* Write the chart legend */
$myPicture->drawLegend(640,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawZoneChart.png");

?>
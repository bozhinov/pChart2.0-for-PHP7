<?php   
/* CAT:Combo */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pIndicator,
	pCharts
};

/* Create the pChart object */
$myPicture = new pDraw(700,350);

/* Populate the pData object */
$Points = [];
for($i=0;$i<=80;$i++) {
	$Points[] = ($i/10)*($i/10);
}
$myPicture->myData->addPoints($Points,"Statistical probability");
$myPicture->myData->setAxisName(0,"Probability");
$myPicture->myData->setAxisUnit(0,"%");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background */
/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,350,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,220, DIRECTION_VERTICAL, ["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,222,700,350, DIRECTION_VERTICAL,["StartColor"=>new pColor(1,138,68,50),"EndColor"=>new pColor(219,231,239,50)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,349,["Color"=>new pColor(0,0,0)]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$scaleSettings = ["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"LabelSkip"=>4,"GridColor"=>new pColor(220,220,220,50),"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE];
$myPicture->drawScale($scaleSettings);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

$myCharts = new pCharts($myPicture);

/* Draw the line of best fit */
$myCharts->drawBestFit(["Ticks"=>4,"Color"=>new pColor(0,0,0,50)]);

/* Draw the line chart */
$myCharts->drawLineChart();

/* Draw the series derivative graph */
$myCharts->drawDerivative(["ShadedSlopeBox"=>TRUE,"CaptionLine"=>TRUE]);

/* Write the chart legend */
$myPicture->drawLegend(570,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Set the default font & shadow settings */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(150,35,"Probability of heart disease",["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE,"Color"=>new pColor(255,255,255)]);

/* Write a label over the chart */
$myPicture->writeLabel(["Statistical probability"],[35],["DrawVerticalLine"=>TRUE,"TitleMode"=>LABEL_TITLE_BACKGROUND,"TitleColor"=>new pColor(255,255,255)]);

/* Create the pIndicator object */ 
$Indicator = new pIndicator($myPicture);

/* Define the indicator sections */
$IndicatorSettings = [
	"Values"=>[35],
	"Unit"=>"%",
	"CaptionPosition"=>INDICATOR_CAPTION_BOTTOM,
	"CaptionColor"=>new pColor(0,0,0),
	"DrawLeftHead"=>FALSE,
	"ValueDisplay"=>INDICATOR_VALUE_LABEL,
	"ValueFontName"=>"pChart/fonts/Forgotte.ttf",
	"ValueFontSize"=>15,
	"IndicatorSections"=> [
			array("Start"=>0,"End"=>29,"Caption"=>"Low","Color"=>new pColor(0,142,176)),
			array("Start"=>30,"End"=>49,"Caption"=>"Moderate","Color"=>new pColor(108,157,49)),
			array("Start"=>50,"End"=>80,"Caption"=>"High","Color"=>new pColor(226,74,14)),
		]
];

$Indicator->draw(60,275,580,30,$IndicatorSettings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.mixed.png");

?>
<?php   
/* CAT:Labels */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pIndicator
};

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([4,12,15,8,5,-5],"Probe 1");
$myPicture->myData->addPoints([7,2,4,14,8,3],"Probe 2");
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->setAxisUnit(0,"°C");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawIndicator() - Create nice looking indicators",["Color"=>new pColor(255,255,255)]);

/* Create the pIndicator object */ 
$Indicator = new pIndicator($myPicture);

$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the indicator sections */
$IndicatorSections = [
	array("Start"=>0,"End"=>199,"Caption"=>"Low","Color"=>new pColor(0,142,176)),
	array("Start"=>200,"End"=>239,"Caption"=>"Moderate","Color"=>new pColor(108,157,49)),
	array("Start"=>240,"End"=>300,"Caption"=>"High","Color"=>new pColor(157,140,49))
];
/* Draw the 1st indicator */
$Indicator->draw(80,50,550,50,["Values"=>[20,230],"FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>15,"IndicatorSections"=>$IndicatorSections,"SubCaptionColorFactor"=>-40]);

/* Define the indicator sections */
$IndicatorSections = [
	array("Start"=>0,"End"=>99,"Caption"=>"Low","Color"=>new pColor(135,49,15)),
	array("Start"=>100,"End"=>119,"Caption"=>"Borderline","Color"=>new pColor(183,62,15)),
	array("Start"=>120,"End"=>220,"Caption"=>"High","Color"=>new pColor(226,74,14))
];
/* Draw the 2nd indicator */
$Indicator->draw(80,160,550,30, [
	"Values"=>[100,201],
	"CaptionPosition"=>INDICATOR_CAPTION_BOTTOM,
	"CaptionColor"=>new pColor(0,0,0),
	"DrawLeftHead"=>FALSE,
	"ValueDisplay"=>INDICATOR_VALUE_LABEL,
	"ValueFontName"=>"pChart/fonts/Forgotte.ttf",
	"ValueFontSize"=>15,
	"IndicatorSections"=>$IndicatorSections
]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawIndicator.png");

?>
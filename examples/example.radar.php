<?php   
/* CAT:Polar and radars */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pRadar
};

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([40,20,15,10,8,4],"ScoreA");  
$myPicture->myData->addPoints([8,10,12,20,30,15],"ScoreB"); 
$myPicture->myData->addPoints([4,8,16,32,16,8],"ScoreC"); 
$myPicture->myData->setSerieDescription("ScoreA","Application A");
$myPicture->myData->setSerieDescription("ScoreB","Application B");
$myPicture->myData->setSerieDescription("ScoreC","Application C");

/* Define the abscissa serie */
$myPicture->myData->addPoints(["Size","Speed","Reliability","Functionalities","Ease of use","Weight"],"Labels");
$myPicture->myData->setAbscissa("Labels");

/* Draw a solid background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

/* Overlay some gradient areas */
$Settings = 
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(194,231,44,50),"EndColor"=>new pColor(43,107,58,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,100),"EndColor"=>new pColor(50,50,50,100)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pRadar - Draw radar charts",["Color"=>new pColor(255,255,255)]);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"Color"=>new pColor(80,80,80)));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Create the pRadar object */ 
$SplitChart = new pRadar($myPicture);

/* Draw a radar chart */ 
$myPicture->setGraphArea(10,25,300,225);
$Options = [
	"Layout"=>RADAR_LAYOUT_STAR,
	"BackgroundGradient"=>["StartColor"=>new pColor(255,255,255,100),"EndColor"=>new pColor(207,227,125,50)],
	"FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6
];
$SplitChart->drawRadar($Options);

/* Draw a radar chart */ 
$myPicture->setGraphArea(390,25,690,225);
$Options = [
	"Layout"=>RADAR_LAYOUT_CIRCLE,
	"LabelPos"=>RADAR_LABELS_HORIZONTAL,
	"BackgroundGradient"=>["StartColor"=>new pColor(255,255,255,50),"EndColor"=>new pColor(32,109,174,30)],
	"FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6
];
$SplitChart->drawRadar($Options);

/* Write the chart legend */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$myPicture->drawLegend(235,205,["Style"=>LEGEND_BOX,"Mode"=>LEGEND_HORIZONTAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.radar.png");

?>
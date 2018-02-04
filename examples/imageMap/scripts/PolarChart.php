<?php   

/* pChart library inclusions */
chdir("../../");
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pRadar;
use pChart\pImageMap\pImageMapFile;

/* Create the pChart object */
/* 							X, Y, TransparentBackground, UniqueID, StorageFolder*/
$myPicture = new pImageMapFile(300,300,FALSE,"PolarChart","temp");

/* Retrieve the image map */
if (isset($_GET["ImageMap"])){
	$myPicture->dumpImageMap();
}

/* Populate the pData object */  
$myPicture->myData->addPoints([10,20,30,40,50,60,70,80,90],"ScoreA"); 
$myPicture->myData->addPoints([20,40,50,12,10,30,40,50,60],"ScoreB"); 
$myPicture->myData->setSerieDescription("ScoreA","Coverage A");
$myPicture->myData->setSerieDescription("ScoreB","Coverage B");

/* Define the abscissa series */
$myPicture->myData->addPoints([40,80,120,160,200,240,280,320,360],"Coord");
$myPicture->myData->setAbscissa("Coord");

/* Draw the background */
$myPicture->drawGradientArea(0,0,300,300,DIRECTION_VERTICAL,["StartColor"=>new pColor(200,200,200,100), "EndColor"=>new pColor(240,240,240,100)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,299,299,["Color"=>new pColor(0,0,0)]);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"Color"=>new pColor(80,80,80)));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,10)]);

/* Create the pRadar object */ 
$SplitChart = new pRadar($myPicture);

/* Draw a radar chart */ 
$SplitChart->myPicture->setGraphArea(10,10,290,290);
$Options = array(
	"RecordImageMap"=>TRUE,
	"LabelPos"=>RADAR_LABELS_HORIZONTAL,
	"BackgroundGradient"=>["StartColor"=>new pColor(255,255,255,50),"EndColor"=>new pColor(32,109,174,30)],
	"AxisRotation"=>0,
	"DrawPoly"=>TRUE,
	"PolyAlpha"=>50,
	"FontName"=>"pChart/fonts/pf_arma_five.ttf",
	"FontSize"=>6
);
$SplitChart->drawPolar($Options);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/PolarChart.png");

?>
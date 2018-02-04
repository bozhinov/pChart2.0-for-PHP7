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
$myPicture = new pImageMapFile(300,300,FALSE,"RadarChart","temp");

/* Retrieve the image map */
if (isset($_GET["ImageMap"])){
	$myPicture->dumpImageMap();
}

/* Populate the pData object */  
$myPicture->myData->addPoints([8,4,6,4,2,7],"ScoreA");  
$myPicture->myData->addPoints([2,7,3,3,1,3],"ScoreB");  
$myPicture->myData->setSerieDescription("ScoreA","Application A");
$myPicture->myData->setSerieDescription("ScoreB","Application B");
$myPicture->myData->setPalette("ScoreA",new pColor(157,196,22));

/* Define the abscissa series */
$myPicture->myData->addPoints(["Speed","Weight","Cost","Size","Ease","Utility"],"Families");
$myPicture->myData->setAbscissa("Families");

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
$myPicture->setGraphArea(10,10,290,290);
$Options = array(
	"RecordImageMap"=>TRUE,
	"DrawPoly"		=>TRUE,
	"WriteValues"	=>TRUE,
	"ValueFontSize"	=>8,
	"Layout"=>RADAR_LAYOUT_CIRCLE,
	"BackgroundGradient"=>["StartColor"=>new pColor(255,255,255,50),"EndColor"=>new pColor(207,227,125,50)]
);
$SplitChart->drawRadar($Options);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/RadarChart.png");

?>
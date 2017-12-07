<?php   

/* pChart library inclusions */
chdir("../../");
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pRadar;
use pChart\pImageMap;

/* Create the pChart object */
/* 							X, Y, TransparentBackground, ImageMapIndex, ImageMapStorageMode, UniqueID, StorageFolder*/
$myPicture = new pImageMap(300,300,FALSE,"ImageMapRadarChart",IMAGE_MAP_STORAGE_FILE,"RadarChart","temp");

/* Retrieve the image map */
if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"])){
	$myPicture->dumpImageMap();
}

/* Populate the pData object */  
$myPicture->myData->addPoints([8,4,6,4,2,7],"ScoreA");  
$myPicture->myData->addPoints([2,7,3,3,1,3],"ScoreB");  
$myPicture->myData->setSerieDescription("ScoreA","Application A");
$myPicture->myData->setSerieDescription("ScoreB","Application B");
$myPicture->myData->setPalette("ScoreA",["R"=>157,"G"=>196,"B"=>22]);

/* Define the abscissa series */
$myPicture->myData->addPoints(["Speed","Weight","Cost","Size","Ease","Utility"],"Families");
$myPicture->myData->setAbscissa("Families");

/* Draw the background */
$myPicture->drawGradientArea(0,0,300,300,DIRECTION_VERTICAL,["StartR"=>200,"StartG"=>200,"StartB"=>200,"EndR"=>240,"EndG"=>240,"EndB"=>240,"Alpha"=>100]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,299,299,["R"=>0,"G"=>0,"B"=>0]);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);

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
	"BackgroundGradient"=>["StartR"=>255,"StartG"=>255,"StartB"=>255,"StartAlpha"=>100,"EndR"=>207,"EndG"=>227,"EndB"=>125,"EndAlpha"=>50]
);
$SplitChart->drawRadar($Options);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/RadarChart.png");

?>
<?php   

/* pChart library inclusions */
chdir("../../");
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pImageMap;
use pChart\pPie;

/* Create the pChart object */
/* 							X, Y, TransparentBackground, ImageMapIndex, ImageMapStorageMode, UniqueID, StorageFolder*/
$myPicture = new pImageMap(300,260,FALSE,"ImageMap3DPieChart",IMAGE_MAP_STORAGE_FILE,"3DPieChart","temp");

/* Retrieve the image map */
if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"])){
	$myPicture->dumpImageMap();
}

/* Populate the pData object */ 
$myPicture->myData->addPoints([40,60,15,10,6,4],"ScoreA");  
$myPicture->myData->setSerieDescription("ScoreA","Application A");

/* Define the abscissa series */
$myPicture->myData->addPoints(array("<10","10<>20","20<>40","40<>60","60<>80",">80"),"Labels");
$myPicture->myData->setAbscissa("Labels");

/* Draw a solid background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,300,300,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,300,260,DIRECTION_VERTICAL,$Settings);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,299,259,["R"=>0,"G"=>0,"B"=>0]);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));

/* Create the pPie object */ 
$PieChart = new pPie($myPicture);

/* Draw an AA pie chart */ 
$PieSettings = array("DrawLabels"=>TRUE,"LabelStacked"=>TRUE,"Border"=>TRUE,"RecordImageMap"=>TRUE);
$PieChart->draw3DPie(160,150,$PieSettings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/3DPieChart.png");

?>
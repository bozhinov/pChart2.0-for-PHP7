<?php   

/* pChart library inclusions */
chdir("../../");
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pImageMap;
use pChart\pCharts;

/* Create the pChart object */
/* 							X, Y, TransparentBackground, ImageMapIndex, ImageMapStorageMode, UniqueID, StorageFolder*/
$myPicture = new pImageMap(700,230,FALSE,"ImageMapBarChart",IMAGE_MAP_STORAGE_FILE,"BarChart.labels","temp");

/* Retrieve the image map */
if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"])){
	$myPicture->dumpImageMap();
}

/* Populate the pData object */ 
$myPicture->myData->addPoints([150,220,300,250,420,200,300,200,100],"Server A");
$myPicture->myData->addPoints([140,VOID,340,300,320,300,200,100,50],"Server B");
$myPicture->myData->setAxisName(0,"Hits");
$myPicture->myData->addPoints(["January","February","March","April","May","June","July","August","September"],"Months");
$myPicture->myData->setSerieDescription("Months","Month");
$myPicture->myData->setAbscissa("Months");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$scaleSettings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Write the chart legend */
$myPicture->drawLegend(580,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);

/* Draw the chart */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);
$myCharts = new pCharts($myPicture);
$myCharts->drawBarChart(["RecordImageMap"=>TRUE]);

/* Replace the labels of the image map */
$Labels = array("Jan: 140","Feb: 0","Mar: 340","Apr: 300","May: 320","Jun: 300","Jul: 200","Aug: 100","Sept: 50");
$myCharts->myPicture->replaceImageMapValues("Server B", $Labels);

/* Replace the titles of the image map */
$Titles = array("Jan 2k11","Feb 2k11","Mar 2k11","Apr 2k11","May 2k11","Jun 2k11","Jul 2k11","Aug 2k11","Sept 2k11");
$myCharts->myPicture->replaceImageMapTitle("Server A", "Second server");
$myCharts->myPicture->replaceImageMapTitle("Server B", $Titles);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/BarChart.labels.png");

?>
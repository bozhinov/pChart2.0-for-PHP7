<?php   

/* pChart library inclusions */
chdir("../../");
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;
use pChart\pImageMap\pImageMapFile;

/* Create the pChart object */
/* 							X, Y, TransparentBackground, UniqueID, StorageFolder*/
$myPicture = new pImageMapFile(700,230,FALSE,"BarChart.labels","temp");

/* Retrieve the image map */
if (isset($_GET["ImageMap"])){
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
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50), "EndColor"=>new pColor(1,138,68,50)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Set the default font */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);

/* Define the chart area */
$myPicture->setGraphArea(60,40,650,200);

/* Draw the scale */
$myPicture->drawScale(["GridColor"=>new pColor(200,200,200),"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);

/* Write the chart legend */
$myPicture->drawLegend(580,12,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw the chart */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);
$myCharts = new pCharts($myPicture);
$myCharts->drawBarChart(["RecordImageMap"=>TRUE]);

/* Replace the labels of the image map */
$Labels = ["Jan: 140","Feb: 0","Mar: 340","Apr: 300","May: 320","Jun: 300","Jul: 200","Aug: 100","Sept: 50"];
$myCharts->myPicture->replaceImageMapValues("Server B", $Labels);

/* Replace the titles of the image map */
$Titles = ["Jan 2k11","Feb 2k11","Mar 2k11","Apr 2k11","May 2k11","Jun 2k11","Jul 2k11","Aug 2k11","Sept 2k11"];
$myCharts->myPicture->replaceImageMapTitle("Server A", "Second server");
$myCharts->myPicture->replaceImageMapTitle("Server B", $Titles);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/BarChart.labels.png");

?>
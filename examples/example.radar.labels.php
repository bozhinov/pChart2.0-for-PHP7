<?php   
/* CAT:Polar and radars */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pDraw,
	pRadar
};

/* Create the pChart object */
$myPicture = new pDraw(300,300);

/* Populate the pData object */
$myPicture->myData->addPoints([4,4,10,10,4,4,15,15,4,4,10,10,4,4,15,15,4,4,10,10,4,4,15,15],"ScoreA");  
$myPicture->myData->setSerieDescription("ScoreA","Application A");
$myPicture->myData->setPalette("ScoreA",["R"=>150,"G"=>5,"B"=>217]);

/* Define the absissa serie */
$myPicture->myData->addPoints([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24],"Time");
$myPicture->myData->setAbscissa("Time");

$myPicture->drawGradientArea(0,0,300,300,DIRECTION_VERTICAL,["StartR"=>200,"StartG"=>200,"StartB"=>200,"EndR"=>240,"EndG"=>240,"EndB"=>240,"Alpha"=>100]);
$myPicture->drawGradientArea(0,0,300,20,DIRECTION_HORIZONTAL,["StartR"=>30,"StartG"=>30,"StartB"=>30,"EndR"=>100,"EndG"=>100,"EndB"=>100,"Alpha"=>100]);
$myPicture->drawLine(0,20,300,20,["R"=>255,"G"=>255,"B"=>255]);
$RectangleSettings = ["R"=>180,"G"=>180,"B"=>180,"Alpha"=>100];

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,299,299,["R"=>0,"G"=>0,"B"=>0]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pRadar - Draw radar charts",["R"=>255,"G"=>255,"B"=>255]);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);

/* Create the pRadar object */ 
$SplitChart = new pRadar($myPicture);

/* Draw a radar chart */ 
$myPicture->setGraphArea(10,25,290,290);
$Options = array("SkipLabels"=>3,"LabelMiddle"=>TRUE,"Layout"=>RADAR_LAYOUT_STAR,"BackgroundGradient"=>["StartR"=>255,"StartG"=>255,"StartB"=>255,"StartAlpha"=>100,"EndR"=>207,"EndG"=>227,"EndB"=>125,"EndAlpha"=>50]);
$SplitChart->drawRadar($Options);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.radar.labels.png");

?>
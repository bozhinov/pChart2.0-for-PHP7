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
$myPicture->myData->addPoints([1,5,6,1,5,7,3,6,5,4,1,0],"ScoreA");  
$myPicture->myData->setSerieDescription("ScoreA","Application A");
$myPicture->myData->setPalette("ScoreA",["R"=>150,"G"=>5,"B"=>217]);

/* Define the abscissa serie */
$myPicture->myData->addPoints([1,2,3,4,5,6,7,8,9,10,11,12],"Time");
$myPicture->myData->setAbscissa("Time");

/* Draw a solid background */
$Settings = array("R"=>179, "G"=>217, "B"=>91, "Dash"=>1, "DashR"=>199, "DashG"=>237, "DashB"=>111);
$myPicture->drawFilledRectangle(0,0,300,300,$Settings);

/* Overlay some gradient areas */
$Settings = array("StartR"=>194, "StartG"=>231, "StartB"=>44, "EndR"=>43, "EndG"=>107, "EndB"=>58, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,300,300,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,300,20,DIRECTION_HORIZONTAL,["StartR"=>30,"StartG"=>30,"StartB"=>30,"EndR"=>100,"EndG"=>100,"EndB"=>100,"Alpha"=>100]);

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
$SplitChart->myPicture->setGraphArea(10,25,290,290);
$Options = array("FixedMax"=>10,"AxisRotation"=>-60,"Layout"=>RADAR_LAYOUT_STAR,"BackgroundGradient"=>["StartR"=>255,"StartG"=>255,"StartB"=>255,"StartAlpha"=>100,"EndR"=>207,"EndG"=>227,"EndB"=>125,"EndAlpha"=>50]);
$SplitChart->drawRadar($Options);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.fixedmax.png");

?>
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

/* Create and populate the pData object */ 
$myPicture->myData->addPoints([10,20,30,40,50,60,70,80,90],"ScoreA"); 
$myPicture->myData->addPoints([20,40,50,30,10,30,40,50,60],"ScoreB"); 
$myPicture->myData->setSerieDescription("ScoreA","Coverage A");
$myPicture->myData->setSerieDescription("ScoreB","Coverage B");

/* Define the absissa serie */
$myPicture->myData->addPoints([40,80,120,160,200,240,280,320,360],"Coord");
$myPicture->myData->setAbscissa("Coord");

$myPicture->drawGradientArea(0,0,300,300,DIRECTION_VERTICAL,["StartR"=>200,"StartG"=>200,"StartB"=>200,"EndR"=>240,"EndG"=>240,"EndB"=>240,"Alpha"=>100]);
$myPicture->drawGradientArea(0,0,300,20, DIRECTION_HORIZONTAL,["StartR"=>30,"StartG"=>30,"StartB"=>30,"EndR"=>100,"EndG"=>100,"EndB"=>100,"Alpha"=>100]);
$myPicture->drawLine(0,20,300,20,["R"=>255,"G"=>255,"B"=>255]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,299,299,["R"=>0,"G"=>0,"B"=>0]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"pRadar - Draw radar charts",["R"=>255,"G"=>255,"B"=>255]);

/* Set the default font properties */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80]);

/* Enable shadow computing */ 
$myPicture->setShadow(FALSE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);

/* Create the pRadar object */ 
$SplitChart = new pRadar($myPicture);

/* Draw a radar chart */ 
$SplitChart->myPicture->setGraphArea(10,25,290,290);
$Options = ["DrawPoly"=>TRUE,"WriteValues"=>TRUE,"ValueFontSize"=>8,"Layout"=>RADAR_LAYOUT_CIRCLE,"BackgroundGradient"=>["StartR"=>255,"StartG"=>255,"StartB"=>255,"StartAlpha"=>100,"EndR"=>207,"EndG"=>227,"EndB"=>125,"EndAlpha"=>50]];
$SplitChart->drawPolar($Options);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.polar.values.png");

?>
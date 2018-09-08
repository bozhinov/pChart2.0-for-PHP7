<?php   
/* CAT:Split chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pCharts
};

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"pSplit - Draw split path charts",["Color"=>new pColor(255,255,255)]);

/* Set the default font properties */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"Color"=>new pColor(80,80,80)]);

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,10)]);

/* Create the pSplit object */
$SplitChart = new pCharts($myPicture);

/* Populate the pData object */  
$SplitChart->myPicture->myData->addPoints([30,20,15,10,8,4],"Score");
$SplitChart->myPicture->myData->addPoints(["End of visit","Home Page","Product Page","Sales","Statistics","Prints"],"Labels");
$SplitChart->myPicture->myData->setAbscissa("Labels");

/* Draw the split chart */
$myPicture->setGraphArea(10,20,340,230);
$SplitChart->drawSplitPath(["TextPos"=>TEXT_POS_RIGHT,"TextPadding"=>10,"Spacing"=>20,"Surrounding"=>40]);
/* Clear the existing points otherwise the next pSplit will add to these */
$SplitChart->myPicture->myData->clearPoints("Score");
$SplitChart->myPicture->myData->clearPoints("Labels");

/* Populate the pData object again */
$SplitChart->myPicture->myData->addPoints([30,20,15],"Score");
$SplitChart->myPicture->myData->addPoints(["UK","FR","ES"],"Labels");
$SplitChart->myPicture->myData->setAbscissa("Labels");

/* Draw the split chart */
$myPicture->setGraphArea(350,50,690,200);
$SplitChart->drawSplitPath(["TextPadding"=>4,"Spacing"=>30,"Surrounding"=>20]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.split.png");

?>
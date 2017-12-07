<?php   
/* CAT:Split chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pDraw,
	pSplit
};

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230, $Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pSplit - Draw splitted path charts",array("R"=>255,"G"=>255,"B"=>255));

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

/* Create the pSplit object */
$SplitChart = new pSplit($myPicture);

/* Populate the pData object */  
$SplitChart->myPicture->myData->addPoints(array(30,20,15,10,8,4),"Score"); 
$SplitChart->myPicture->myData->addPoints(array("End of visit","Home Page","Product Page","Sales","Statistics","Prints"),"Labels");
$SplitChart->myPicture->myData->setAbscissa("Labels");

/* Draw the split chart */
$Settings = array("TextPos"=>TEXT_POS_RIGHT,"TextPadding"=>10,"Spacing"=>20,"Surrounding"=>40);
$myPicture->setGraphArea(10,20,340,230);
$SplitChart->drawSplitPath($Settings);

/* Populate the pData object again */
$SplitChart->myPicture->myData->addPoints(array(30,20,15),"Score"); 
$SplitChart->myPicture->myData->addPoints(array("UK","FR","ES"),"Labels");
$SplitChart->myPicture->myData->setAbscissa("Labels");

/* Draw the split chart */
$Settings = array("TextPadding"=>4,"Spacing"=>30,"Surrounding"=>20);
$myPicture->setGraphArea(350,50,690,200);
$SplitChart->drawSplitPath($Settings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.split.png");

?>
<?php   
/* CAT:Misc */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([24,-25,26,25,25],"Temperature");
$myPicture->myData->setAxisName(0,"Temperatures");
$myPicture->myData->addPoints(["Jan","Feb","Mar","Apr","May","Jun"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,["StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawThreshold() - draw a threshold in the charting area",["R"=>255,"G"=>255,"B"=>255]);

/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawText(250,55,"My chart title",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

/* Draw the scale and do some cosmetics */ 
$myPicture->setGraphArea(60,60,450,190);
$myPicture->drawFilledRectangle(70,70,440,180,["R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10]);
$myPicture->drawScale(["XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"DrawSubTicks"=>TRUE]);
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20]); 

/* Draw static thresholds */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10));
$myPicture->drawXThreshold(["Feb"],["ValueIsLabel"=>TRUE,"WriteCaption"=>TRUE,"Caption"=>"Step 1","Alpha"=>70,"Ticks"=>1]);
$myPicture->drawXThreshold([2],["WriteCaption"=>TRUE,"Caption"=>"Step 2","Alpha"=>70,"Ticks"=>2,"R"=>0,"G"=>0,"B"=>255]);

/* Disable shadow computing */ 
$myPicture->setShadow(FALSE);

/* Draw the scale and do some cosmetics */ 
$myPicture->setGraphArea(500,60,670,190);
$myPicture->drawFilledRectangle(505,65,665,185,["R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10]);
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
$myPicture->drawScale(array("XMargin"=>5,"YMargin"=>5,"Floating"=>TRUE,"Pos"=>SCALE_POS_TOPBOTTOM,"DrawSubTicks"=>TRUE));
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20]); 

/* Draw static thresholds */ 
$myPicture->drawXThreshold([1,3],["Alpha"=>70,"Ticks"=>1]);
$myPicture->drawXThreshold([2],["Alpha"=>70,"Ticks"=>2,"R"=>0,"G"=>0,"B"=>255]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawXThreshold.png");

?>
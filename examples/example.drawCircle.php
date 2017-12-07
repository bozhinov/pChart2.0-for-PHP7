<?php   
/* CAT:Drawing */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Draw the background */
$Settings = ["R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107];
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

/* Overlay with a gradient */
$Settings = ["StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50];
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,["StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"drawCircle() - Transparency & colors",["R"=>255,"G"=>255,"B"=>255]);

/* Draw some circles */ 
$myPicture->drawCircle(100,125,50,50,["R"=>213,"G"=>226,"B"=>0,"Alpha"=>100]);
$myPicture->drawCircle(140,125,50,50,["R"=>213,"G"=>226,"B"=>0,"Alpha"=>70]);
$myPicture->drawCircle(180,125,50,50,["R"=>213,"G"=>226,"B"=>0,"Alpha"=>40]);
$myPicture->drawCircle(220,125,50,50,["R"=>213,"G"=>226,"B"=>0,"Alpha"=>20]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20]);

/* Draw a customized circles */ 
$CircleSettings = ["R"=>209,"G"=>31,"B"=>27,"Alpha"=>100];
$myPicture->drawCircle(480,60,20,20,$CircleSettings);

/* Draw a customized circles */ 
$CircleSettings = ["R"=>209,"G"=>125,"B"=>27,"Alpha"=>100];
$myPicture->drawCircle(480,100,30,20,$CircleSettings);

/* Draw a customized circles */ 
$CircleSettings = ["R"=>209,"G"=>198,"B"=>27,"Alpha"=>100,"Ticks"=>4];
$myPicture->drawCircle(480,140,40,20,$CircleSettings);

/* Draw a customized circles */ 
$CircleSettings = ["R"=>134,"G"=>209,"B"=>27,"Alpha"=>100,"Ticks"=>4];
$myPicture->drawCircle(480,180,50,20,$CircleSettings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawCircle.png");

?>
<?php   
/* CAT:Drawing */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

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
$myPicture->drawText(10,13,"drawFromGIF() - add pictures to your charts",["R"=>255,"G"=>255,"B"=>255]);

/* Turn off shadow computing */ 
$myPicture->setShadow(FALSE);

/* Draw a GIF object */
$myPicture->drawFromGIF(180,50,"examples/resources/computer.gif");

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20]);

/* Draw a GIF object */
$myPicture->drawFromGIF(400,50,"examples/resources/computer.gif");

/* Write the legend */
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20]);
$TextSettings = array("R"=>255,"G"=>255,"B"=>255,"FontSize"=>10,"FontName"=>"pChart/fonts/calibri.ttf","Align"=>TEXT_ALIGN_BOTTOMMIDDLE);
$myPicture->drawText(240,200,"Without shadow",$TextSettings);
$myPicture->drawText(460,200,"With enhanced shadow",$TextSettings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawFromGIF.png");

?>
<?php   
/* CAT:Barcode */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pDraw,
	pBarcode39
};

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Draw the background */
$Settings =["R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107];
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

/* Overlay with a gradient */
$Settings = ["StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50];
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,["StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80]);

/* Draw the picture border */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Write the title */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"Barcode 39 - Add barcode to your pictures",["R"=>255,"G"=>255,"B"=>255]);

/* Create the barcode 39 object */
$Barcode = new pBarcode39($myPicture);

/* Draw a simple barcode */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$Settings = ["ShowLegend"=>TRUE,"DrawArea"=>TRUE];
$Barcode->draw("pChart Rocks!",50,50,$Settings);

/* Draw a rotated barcode */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>12]);
$Settings = ["ShowLegend"=>TRUE,"DrawArea"=>TRUE,"Angle"=>90];
$Barcode->draw("Turn me on",650,50,$Settings);

/* Draw a rotated barcode */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>12]);
$Settings = ["R"=>255,"G"=>255,"B"=>255,"AreaR"=>150,"AreaG"=>30,"AreaB"=>27,"ShowLegend"=>TRUE,"DrawArea"=>TRUE,"Angle"=>350,"AreaBorderR"=>70,"AreaBorderG"=>20,"AreaBorderB"=>20];
$Barcode->draw("Do what you want !",290,140,$Settings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.barcode39.png");

?>
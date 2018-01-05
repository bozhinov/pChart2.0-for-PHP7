<?php   
/* CAT:Barcode */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pBarcode39
};

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL, ["StartColor"=>$Start=new pColor(219,231,139,50), "EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL, ["StartColor"=>$Start=new pColor(0,0,0,80), "EndColor"=>new pColor(50,50,50,80)]);

/* Draw the picture border */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the title */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"Barcode 39 - Add barcode to your pictures",["Color"=>new pColor(255,255,255)]);

/* Create the barcode 39 object */
$Barcode = new pBarcode39($myPicture);

/* Draw a simple barcode */
$Barcode->myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$Barcode->draw("pChart Rocks!",50,50,["ShowLegend"=>TRUE,"DrawArea"=>TRUE]);

/* Draw a rotated barcode */
$Barcode->myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>12]);
$Barcode->draw("Turn me on",650,50,["ShowLegend"=>TRUE,"DrawArea"=>TRUE,"Angle"=>90]);

/* Draw a rotated barcode */
$Barcode->draw("Do what you want !",290,140,[
	"Color"=>new pColor(255,255,255),
	"AreaColor"=>new pColor(150,30,27),
	"AreaBorderColor"=>new pColor(70,20,20),
	"ShowLegend"=>TRUE,
	"DrawArea"=>TRUE,
	"Angle"=>350
	]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.barcode39.png");

?>
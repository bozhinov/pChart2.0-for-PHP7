<?php   
/* CAT:Barcode */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw
};

use Barcodes\{
	Barcodes,
	BarColor,
	Encoders\Codes
};

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Write the title */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/PressStart2P-Regular.ttf","FontSize"=>6]);
$myPicture->drawText(10,15,"Barcode 39 - Add barcode to your pictures",["Color"=>new pColor(0)]);

/* Create the barcode 39 object */
$Generator = new Barcodes("code39");

/* Draw a simple barcode */
//$Barcode->myPicture->setFontProperties(["FontSize"=>6]);
$Generator->forPChart($myPicture, "pChart Rocks!", [], 50,50);

/* Draw a rotated barcode */
//$Barcode->myPicture->setFontProperties(["FontName"=>"pChart/fonts/Cairo-Regular.ttf","FontSize"=>12]);
$Generator->forPChart($myPicture, "Turn me on", ["Angle"=>90], 600,20);

//$Barcode->myPicture->setFontProperties(["FontName"=>"pChart/fonts/Cairo-Regular.ttf","FontSize"=>12]);
$Generator->forPChart($myPicture, "Turn me on", ["Angle"=>350], 300,20);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.barcode39.png");

?>
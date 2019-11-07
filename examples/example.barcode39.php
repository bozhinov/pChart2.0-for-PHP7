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

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL, ["StartColor"=>$Start=new pColor(219,231,139,50), "EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL, ["StartColor"=>$Start=new pColor(0,0,0,80), "EndColor"=>new pColor(50,50,50,80)]);

/* Write the title */
$myPicture->setFontProperties(["FontName"=>"fonts/PressStart2P-Regular.ttf","FontSize"=>6]);
$myPicture->drawText(10,15,"Barcode 39 - Add barcode to your pictures",["Color"=>new pColor(255)]);

/* Create the barcode 39 object */
$Generator = new Barcodes("code39");

/* TODO
$myPicture->drawPolygon());

/* Draw a simple barcode */
$Generator->forPChart($myPicture, "pChart Rocks!", ['palette' => [1 => new BarColor(255,0,0)], "label" => ['Color' => new BarColor(255,0,0)]], 50,50);

/* Draw a rotated barcode */
$Generator->forPChart($myPicture, "Turn me on", ["Angle"=>90, "label" => ["TTF" => "fonts/Cairo-Regular.ttf", "Size" => 12, "Offset" => 1]], 590,30);

/* Draw a rotated barcode - no label */
$Generator->forPChart($myPicture, "Turn me on", ["Angle"=>350, "label" => ["Skip" => TRUE]], 300,50);

/* Turn black and white */
$myPicture->setFilter(IMG_FILTER_GRAYSCALE);
$myPicture->setFilter(IMG_FILTER_CONTRAST, -100);

/* Render the picture with higest compression */
$myPicture->autoOutput("temp/example.barcode39.png", 9);


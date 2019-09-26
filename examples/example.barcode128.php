<?php   
/* CAT:Barcode */

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

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL, ["StartColor"=>new pColor(219,231,139,50), "EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80), "EndColor"=>new pColor(50,50,50,80)]);

/* Draw the border */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0)]);

/* Write the title */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/PressStart2P-Regular.ttf","FontSize"=>6]);
$myPicture->drawText(10,15,"Barcode 128 - Add barcode to your pictures",["Color"=>new pColor(255)]);

/* Create the barcode 128 object */
$Generator = new Barcodes("code128");

/* Draw a simple barcode */
$Generator->forPChart($myPicture, "pChart Rocks!", ["label" => ['Size' => 2]], 50,50);

/* Draw a rotated barcode */
$Generator->forPChart($myPicture, "Turn me on", ["Angle"=>90, "label" => ["TTF" => "pChart/fonts/Cairo-Regular.ttf", "Size" => 12, "Offset" => 1]], 590,50);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawbarcode128.png");

?>
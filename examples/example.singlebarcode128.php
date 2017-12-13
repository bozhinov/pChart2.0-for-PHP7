<?php   
/* CAT:Barcode */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pDraw,
	pBarcode128
};

/* String to be written on the barcode */
$String = "This is a test";

/* Create the barcode 128 object */
$Barcode = new pBarcode128(new pDraw(1, 1));

$Settings = ["ShowLegend"=>TRUE,"DrawArea"=>TRUE];

/* Resize to the barcode projected size */
list($XSize, $YSize) = $Barcode->getProjection($String, $Settings);

$Barcode->myPicture->resize($XSize, $YSize);

/* Set the font to use */
$Barcode->myPicture->setFontProperties(["FontName"=>"pChart/fonts/GeosansLight.ttf"]);

/* Render the barcode */
$Barcode->draw($String,10,10,$Settings);

/* Render the picture (choose the best way) */
$Barcode->myPicture->autoOutput("temp/example.singlebarcode128.png");

?>
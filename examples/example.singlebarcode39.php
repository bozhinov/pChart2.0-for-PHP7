<?php   
/* CAT:Barcode */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pDraw,	
	pBarcode\pBarcode39
};

/* String to be written on the barcode */
$String = "This is a test";

$Settings = ["ShowLegend"=>TRUE,"DrawArea"=>TRUE];

/* Resize to the barcode projected size */
list($XSize, $YSize) = pBarcode39::getProjection($String, $Settings);

/* Create the barcode 39 object */
$Barcode = new pBarcode39(new pDraw($XSize, $YSize));

/* Set the font to use */
$Barcode->myPicture->setFontProperties(["FontName"=>"pChart/fonts/GeosansLight.ttf"]);

/* Render the barcode */
$Barcode->draw($String,10,10,$Settings);

/* http://php.net/manual/en/function.imagefilter.php */
$Barcode->myPicture->setFilter(IMG_FILTER_GRAYSCALE);

/* Render the picture (choose the best way) */
/* Momchil: applied filters + the gray scale palette results in significantly smaller image */
$Barcode->myPicture->autoOutput("temp/example.singlebarcode39.png", 9, PNG_ALL_FILTERS);

?>
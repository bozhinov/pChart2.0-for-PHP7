<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\pBarcodes1D;

/* Create a pChart object */ 
$myPicture = new pDraw(250,60);

$opts = [
	'height'=> 40,
	'width' => 220,
	"label" => [
		'height'=> 10,
		'size' 	=> 1,
		'color' => (new pColor())->fromHex("2AFF55")
	]
];

# unable to verify. could not find app to scan it.
$barcodes = new pBarcodes1D(BARCODES_ENGINE_CODE11, $myPicture);
$barcodes->draw("1232342342342343312", 0, 0, $opts);

$myPicture->autoOutput("temp/example.barcodes.encode11.png");

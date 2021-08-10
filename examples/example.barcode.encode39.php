<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\pBarcodes1D;

/* Create a pChart object */ 
$myPicture = new pDraw(700,230);

$opts = [
	"label" => [
		'height'=> 10,
		'size' 	=> 1,
		'color' => (new pColor())->fromHex("2AFF55")
	]
];

$barcodes = new pBarcodes1D(BARCODES_ENGINE_CODE39, $myPicture);
$barcodes->draw("12250000234502", 1, 1, $opts);

$myPicture->autoOutput("temp/example.barcodes.encode39.png");

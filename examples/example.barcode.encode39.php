<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\Barcodes\{
	Barcodes,
	Encoders\Codes
};

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);

$opts = [
	"label" => [
		'Height'=> 10,
		'Size' 	=> 1,
		'Color' => (new pColor())->fromHex("2AFF55")
	]
];

$barcodes = new Barcodes($myPicture);
$barcodes->draw("12250000234502", "code39", $opts);

$myPicture->autoOutput("temp/encode39.png");

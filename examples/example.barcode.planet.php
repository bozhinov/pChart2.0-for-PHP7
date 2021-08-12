<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\pBarcodes1D;

/* Create a pChart object */ 
$myPicture = new pDraw(700,230);
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

$opts = [
	"mode" => "planet",
	"scale" => 2,
	"ratio" => 3,
	"padding" => 10,
	"height" => 20,
	"label" => [
		'height'=> 10,
		'size' 	=> 1,
		'color' => new pColor(0)
	]
];

# unable to verify. could not find app to scan it.
$barcodes = new pBarcodes1D(BARCODES_ENGINE_POSTNET, $myPicture);
$barcodes->draw("123456789", 0, 0, $opts);

$myPicture->autoOutput("temp/example.barcodes.planet.png");

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
	"mode" => "E+",
	"nobackground" => true,
	"label" => [
		'height'=> 10,
		'size' 	=> 3,
		'offset' => 5,
		'color' => (new pColor())->fromHex("ffffff")
	]
];

$barcodes = new pBarcodes1D(BARCODES_ENGINE_CODE39, $myPicture);
$barcodes->draw("12250000234502{}", 1, 1, $opts);

$myPicture->autoOutput("temp/example.barcodes.encode39.png");

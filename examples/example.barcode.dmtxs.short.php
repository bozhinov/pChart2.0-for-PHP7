<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\pCharts;
use pChart\pBarcodes\{
	pBarcodes,
	Encoders\DMTX
};

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(1000,1000);

$opts = [
	"BackgroundColor" => (new pColor())->fromHex("#FF00FF"),
	"palette" => [
			0 => new pColor(255), 	// CS - Color of spaces
			1 => new pColor(0) 		// CM - Color of modules
		],
	"widths" => [
		'QuietArea' => 4
	]
];

$data = 8675309;
$barcodes = new pBarcodes($myPicture);
$barcodes->encode($data, "dmtxs", $opts);

$myPicture->autoOutput("temp/example_dmtxs_short.png");

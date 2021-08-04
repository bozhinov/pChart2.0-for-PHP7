<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\Barcodes\MatrixCodes;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(250,250);
$myPicture->drawFilledRectangle(0,0,250,250,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

$opts = [
	"palette" => [
			0 => new pColor(255), 	// CS - Color of spaces
			1 => new pColor(0) 		// CM - Color of modules
		],
	"widths" => [
		'QuietArea' => 4
	]
];

$data = 154703;
$barcodes = new MatrixCodes($myPicture);
$barcodes->set_start_position($x = 10, $y = 10);
$barcodes->draw($data, "dmtxs", $opts);

$myPicture->autoOutput("temp/example_dmtxs_short.png");

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
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

$barcodes = new Barcodes($myPicture);
$barcodes->set_start_position($x = 10, $y = 10);
$barcodes->draw("Do what you want !", "code128", []);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/encode128.png");

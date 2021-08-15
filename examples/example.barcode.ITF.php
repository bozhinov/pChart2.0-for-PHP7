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

$opts = [];

$barcodes = new pBarcodes1D(BARCODES_ENGINE_ITF, $myPicture);
$barcodes->draw("12250000234502", 1, 1, $opts);

$myPicture->autoOutput("temp/example.barcodes.ITF.png");

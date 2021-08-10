<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\pBarcodes1D;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

$data = "Do what you want !";

$barcodes = new pBarcodes1D(BARCODES_ENGINE_CODE128, $myPicture);
$barcodes->draw($data, 10, 10, []);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.barcodes.encode128.png");

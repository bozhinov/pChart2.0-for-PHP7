<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\pBarcodes2D;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,450);
$myPicture->drawFilledRectangle(0,0,700,450,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

$aztec = new pBarcodes2D(BARCODES_ENGINE_AZTEC, $myPicture);

// Text to be encoded
$text = 'Hello World 3 4 5 asasdas22345 . 456!';

// draw the data
$aztec->draw($text, 20, 20);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.barcodes.aztec.pairCode.png');

<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pBarcodes2D;

// Text to be encoded
$text = 'Hello World!';

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);
$aztec = new pBarcodes2D(BARCODES_ENGINE_AZTEC, $myPicture);

// draw the data
$aztec->draw($text, $posX = 10, $posY = 10);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.barcodes.aztec.basic.png');

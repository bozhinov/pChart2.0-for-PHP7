<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pBarcodes2D;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);
$aztec = new pBarcodes2D(BARCODES_ENGINE_AZTEC, $myPicture);

// Text to be encoded
$text = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~';

// draw the data
$aztec->draw($text, 1, 1, ["hint" => BARCODES_AZTEC_HINT_BINARY]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.barcodes.aztec.binary.png');

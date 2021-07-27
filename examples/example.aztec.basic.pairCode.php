<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\Aztec\Aztec;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);

// Text to be encoded
$text = 'Hello World 3 4 5 asasdas22345 . 456!';

// draw the data
$aztec = new Aztec($myPicture);
$aztec->draw($text);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.aztec.pairCode.png');

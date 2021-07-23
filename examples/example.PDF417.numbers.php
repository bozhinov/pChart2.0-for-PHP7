<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\PDF417\PDF417;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);

// Text to be encoded into the barcode
$text = '123123123123123123123123123123';

// Encode the data, returns a BarcodeData object
$pdf417 = new PDF417($myPicture);
$pdf417->encode($text);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.pdf417.numbers.png");

<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\PDF417\PDF417;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);

// Text to be encoded into the barcode
$text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur
imperdiet sit amet magna faucibus aliquet. Aenean in velit in mauris imperdiet
scelerisque. Maecenas a auctor erat.';

// draw the data, returns a BarcodeData object
$pdf417 = new PDF417($myPicture);
$pdf417->draw($text);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.pdf417.basic.png");

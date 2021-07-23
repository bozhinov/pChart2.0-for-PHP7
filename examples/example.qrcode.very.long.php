<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\QRCode\QRCode;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,700);

$QRCode = new QRCode($myPicture);
$QRCode->encode(str_repeat("A", 256), ['level' => "Q", 'size' => 10, 'margin' => 4]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.QRcode.very.long.png');
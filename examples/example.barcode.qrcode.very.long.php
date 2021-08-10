<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pBarcodes2D;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,700);

$QRCode = new pBarcodes2D(BARCODES_ENGINE_QRCODE, $myPicture);
$QRCode->draw(str_repeat("A", 256), 1, 1, ['level' => BARCODES_QRCODE_LEVEL_Q, 'scale' => 10, 'padding' => 4]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.barcodes.QRcode.very.long.png');
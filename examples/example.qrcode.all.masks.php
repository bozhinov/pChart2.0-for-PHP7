<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\Barcodes\QRCode;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,700);

$QRCode = new QRCode($myPicture);
$QRCode->draw('https://github.com/bozhinov/PHP-QRCode-fork', ['level' => "Q", 'scale' => 10, 'padding' => 4]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.QRcode.all.masks.png');

<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\QRCode\QRCode;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,700);

$QRCode = new QRCode($myPicture);
$QRCode->draw('http://www.test.bg/12341234 TEST TEST  TEST  TEST  TEST  TEST  TEST  TEST  TEST   TEST   TEST   TESTTSTS');

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.QRcode.long.png');

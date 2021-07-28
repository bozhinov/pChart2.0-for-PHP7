<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\Barcodes\QRCode;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,700);

if (function_exists("mb_internal_encoding")){
	mb_internal_encoding('SJIS');
} else {
	die("mb_string ext is required");
}

$QRCode = new QRCode($myPicture);
$QRCode->draw('���{�̕ۈ牀', ['level' => "Q", 'scale' => 10, 'padding' => 4, 'hint' => "Kanji"]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.QRcode.kanji.png');

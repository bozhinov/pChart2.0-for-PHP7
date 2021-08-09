<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pBarcodes2D;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,700);

if (function_exists("mb_internal_encoding")){
	mb_internal_encoding('SJIS');
} else {
	die("mb_string ext is required");
}

$QRCode = new pBarcodes2D(BARCODES_ENGINE_QRCODE, $myPicture);

$options =  [
	'level' => BARCODES_QRCODE_LEVEL_Q,
	'scale' => 10,
	'padding' => 4,
	'hint' => BARCODES_QRCODE_HINT_KANJI
	];

$QRCode->draw('“ú–{‚Ì•Ûˆç‰€', 1, 1, $options);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.barcodes.qrcode.kanji.png');

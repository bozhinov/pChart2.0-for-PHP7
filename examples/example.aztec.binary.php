<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\Aztec\Aztec;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);

// Text to be encoded
$text = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~';

// Encode the data
$aztec = new Aztec($myPicture);
$aztec->encode($text, ["hint" => "binary"]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.aztec.binary.png');

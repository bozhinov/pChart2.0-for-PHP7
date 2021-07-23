<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\Aztec\Aztec;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);

// Text to be encoded
#$text = str_repeat("a", 32);
$text = "Rock-a-bye, baby On the treetop When the wind blows The cradle will rock If the bough breaks The cradle will fall But mama will catch you Cradle and all";

// Encode the data
$aztec = new Aztec($myPicture);
$aztec->encode($text, ["hint" => "binary"]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.aztec.binary.long.png');

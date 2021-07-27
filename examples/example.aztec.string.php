<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\Aztec\Aztec;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);

// Text to be encoded
$text = 'Hello World!';

# consider ini_get('default_charset') != ('UTF-8' || 'ISO-8859-1')
$text = iconv('UTF-8', 'ISO-8859-1//IGNORE', $text);

// draw the data
$aztec = new Aztec($myPicture);
$aztec->draw($text, ["hint" => "binary"]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.aztec.string.png');

<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\Aztec\Aztec;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,450);
$myPicture->drawFilledRectangle(0,0,700,450,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

// Text to be encoded
$text = 'Hello World 3 4 5 asasdas22345 . 456!';

// draw the data
$aztec = new Aztec($myPicture);
$aztec->set_start_position($x = 10, $y = 20);
$aztec->draw($text);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.aztec.pairCode.png');

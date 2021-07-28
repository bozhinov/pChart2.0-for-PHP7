<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\Barcodes\QRCode;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,700);
$myPicture->drawFilledRectangle(0,0,700,700,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

$QRCode = new QRCode($myPicture);
$QRCode->set_start_position($x = 10, $y = 10);
$QRCode->draw('http://www.test.bg/12341234 TEST TEST  TEST  TEST  TEST  TEST  TEST  TEST  TEST   TEST   TEST   TESTTSTS', ["error_correction" => "L", "scale" => 7, "padding" => 4]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.QRcode.basic.png');

/* Usage

*/
# Levels
# https://www.qrcode.com/en/about/error_correction.html
# Level L = ~7%
# Level M = ~15%
# Level Q = ~25%
# Level H = ~30%

# Encoding hints
# https://www.thonky.com/qr-code-tutorial/data-encoding
# Encoding Mode		Maximum number of characters a 40-L code can contain
# Numeric		7089 characters
# Alphanumeric		4296 characters
# Byte			2953 characters
# Kanji			1817 characters
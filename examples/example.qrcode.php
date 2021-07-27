<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\QRCode\QRCode;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,700);

$QRCode = new QRCode($myPicture);
$QRCode->draw('http://www.test.bg/12341234 TEST TEST  TEST  TEST  TEST  TEST  TEST  TEST  TEST   TEST   TEST   TESTTSTS', ["error_correction" => "L", "matrix_point_size" => 7, "margin" => 4]);

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
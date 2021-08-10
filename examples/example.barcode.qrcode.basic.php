<?php
/* CAT:Barcodes */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pColor;
use pChart\pBarcodes2D;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,700);
$myPicture->drawFilledRectangle(0,0,700,700,["Color"=>new pColor(179,217,91), "Dash"=>TRUE, "DashColor"=>new pColor(199,237,111)]);

$QRCode = new pBarcodes2D(BARCODES_ENGINE_QRCODE, $myPicture);
$QRCode->draw(
	'http://www.test.bg/long long long string',
	$x = 20,
	$y = 20,
	[
		'palette' => ['color' => new pColor(255), 'bgColor' => new pColor(0)], // colors are reversed
		"error_correction" => BARCODES_QRCODE_LEVEL_L,
		"scale" => 7,
		"padding" => 4,
		"random_mask" => 4
	]
	);

/* Render the picture (choose the best way) */
$myPicture->autoOutput('temp/example.barcodes.qrcode.basic.png');

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
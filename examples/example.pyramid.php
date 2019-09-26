<?php
/* CAT:Misc */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pPyramid;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(1800,800);
$myPyramid = new pPyramid($myPicture);

$Settings = ["Color" => new pColor(0),"NoFill" => TRUE];

$myPyramid->drawPyramid($X = 100, $Y = 600, $Base = 400, $Height = 200, $Segments = 5, $Settings);

$palette_light = [
	[239,210,121,100],
	[149,203,233,100],
	[2,71,105,100],
	[175,215,117,100],
	[44,87,0,100],
	[222,157,127,100]
];

$myPicture->myData->loadPalette($palette_light, $overwrite=TRUE);

$Settings = ["NoFill" => FALSE];

$myPyramid->drawReversePyramid($X = 600, $Y = 600, $Base = 400, $Height = 200, $Segments = 3, $Settings);

$Settings = ["NoFill" => FALSE];
$myPyramid->draw3DPyramid($X = 1100, $Y = 600, $Base = 400, $Height = 200, $Segments = 4, $Settings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.pyramid.png");

?>
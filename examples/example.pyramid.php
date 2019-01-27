<?php
/* CAT:Misc */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pPyramid;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(800,800);

(new pPyramid($myPicture))->drawPyramid($X = 100, $Y = 600, $Base = 400, $Height = 200, $Segments = 5);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.pyramid.png");

?>
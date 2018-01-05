<?php
/* CAT:Misc */

/* Include all the classes */ 
require_once("bootstrap.php");

use pChart\pDraw;
use pChart\pCharts;

/* Create a pChart object and associate your dataset */ 
$myPicture = new pDraw(700,230);

/* Add data in your dataset */ 
$myPicture->myData->addPoints([1,3,4,3,5]);

/* Choose a nice font */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11]);

/* Define the boundaries of the graph area */
$myPicture->setGraphArea(60,40,670,190);

/* Draw the scale, keep everything automatic */ 
$myPicture->drawScale();

/* Draw the scale, keep everything automatic */ 
(new pCharts($myPicture))->drawSplineChart();

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.basic.png");

?>
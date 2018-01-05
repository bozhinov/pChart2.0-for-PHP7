<?php   

$Seed = (!isset($_GET["Seed"])) ? "Unknown" : $_GET["Seed"];

/* Include all the classes */ 
chdir("../");
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,["StartColor"=>new pColor(220,220,220,100), "EndColor"=>new pColor(180,180,180,100)]);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,  ["StartColor"=>new pColor(220,220,220,50), "EndColor"=>new pColor(180,180,180,50)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(150,150,150)]);

/* Write the title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/advent_light.ttf","FontSize"=>40));
$myPicture->drawText(130,130,"Delayed loading script",["Color"=>new pColor(255,255,255)]);

/* Write the seed # */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/advent_light.ttf","FontSize"=>10));
$myPicture->drawText(130,140,"Seed # : ".$Seed, ["Color"=>new pColor(255,255,255)]);

/* Draw a bezier curve */ 
$myPicture->drawBezier(360,170,670,120,430,100,560,190,["Color"=>new pColor(255,255,255),"Ticks"=>4,"DrawArrow"=>TRUE,"ArrowTwoHeads"=>TRUE]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/draw.png");

?>
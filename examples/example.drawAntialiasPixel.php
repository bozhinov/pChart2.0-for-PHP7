<?php   
/* CAT:Drawing */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);
$myPicture->drawGradientArea(0,0,700,230, DIRECTION_VERTICAL, ["StartColor"=>new pColor(180,193,91,100),"EndColor"=>new pColor(120,137,72,100)]);
$myPicture->drawGradientArea(0,0,700,230, DIRECTION_HORIZONTAL, ["StartColor"=>new pColor(180,193,91,20),"EndColor"=>new pColor(120,137,72,20)]);
$myPicture->drawGradientArea(0,0,700,20,  DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,100),"EndColor"=>new pColor(50,50,50,100)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"drawAntialiasPixel() - Drawing anti-aliased pixel with transparency",["Color"=>new pColor(255,255,255)]);

/* Draw some alpha pixels */ 
$PixelColor = new pColor(128,255,255);
for($X=0;$X<=160;$X++)
{
	for($Y=0;$Y<=160;$Y++)
	{
		$PixelColor->G = 255-$Y;
		$PixelColor->B = $X;
		$PixelColor->AlphaSet(cos(deg2rad($X*2))*50+50);
		$myPicture->drawAntialiasPixel($X*2+20.4,$Y+45,$PixelColor);
		$myPicture->drawAntialiasPixel($X+400,$Y+45,$PixelColor);
	}
}
/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawantialiaspixel.png");

?>
<?php   
/* CAT:Drawing */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartR"=>180,"StartG"=>193,"StartB"=>91,"EndR"=>120,"EndG"=>137,"EndB"=>72,"Alpha"=>100]);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,["StartR"=>180,"StartG"=>193,"StartB"=>91,"EndR"=>120,"EndG"=>137,"EndB"=>72,"Alpha"=>20]);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,["StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["R"=>0,"G"=>0,"B"=>0]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"drawAntialiasPixel() - Drawing antialiased pixel with transparency",["R"=>255,"G"=>255,"B"=>255]);

/* Draw some alpha pixels */ 
for($X=0;$X<=160;$X++)
{
	for($Y=0;$Y<=160;$Y++)
	{
		$PixelSettings = ["R"=>128,"G"=>255-$Y,"B"=>$X,"Alpha"=>cos(deg2rad($X*2))*50+50];
		$myPicture->drawAntialiasPixel($X*2+20.4,$Y+45,$PixelSettings);
		$myPicture->drawAntialiasPixel($X+400,$Y+45,$PixelSettings);
	}
}
/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawantialiaspixel.png");

?>
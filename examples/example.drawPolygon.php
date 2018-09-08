<?php   
/* CAT:Drawing */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL, ["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20, DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawPolygon - Draw polygons",["Color"=>new pColor(255,255,255)]);

/* Enable shadow computing */
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,10)]);

/* Create some filling thresholds */
$Threshold = [
	array("MinX"=>100,"MaxX"=>60, "Color"=>new pColor(200,200,200,50)),
	array("MinX"=>140,"MaxX"=>100,"Color"=>new pColor(220,220,220,50)),
	array("MinX"=>180,"MaxX"=>140,"Color"=>new pColor(240,240,240,50))
];
/* Draw some polygons */
$Step  = 8;
$White = ["Threshold"=>$Threshold,"Color"=>new pColor(255,255,255,100),"BorderColor"=>new pColor(0,0,0,100)];

for($i=1;$i<=4;$i++)
{
	$Points = [];
	for($j=0;$j<=360;$j=$j+(360/$Step))
	{
		$Points[] = cos(deg2rad($j))*50+($i*140);
		$Points[] = sin(deg2rad($j))*50+120;
	}
	$myPicture->drawPolygon($Points,$White);
	$Step = $Step * 2;
}

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawPolygon.png");

?>
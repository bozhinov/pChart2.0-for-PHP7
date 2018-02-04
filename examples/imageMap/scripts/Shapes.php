<?php   

/* pChart library inclusions */
chdir("../../");
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pImageMap\pImageMapFile;

/* Create the pChart object */
/* 							X, Y, TransparentBackground, UniqueID, StorageFolder*/
$myPicture = new pImageMapFile(700,230,FALSE,"Shapes","temp");

/* Retrieve the image map */
if (isset($_GET["ImageMap"])){
	$myPicture->dumpImageMap();
}

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50), "EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,80), "EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"drawFilledRectangle() - Transparency & colors",["Color"=>new pColor(255,255,255)]);

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,20)]);

/* Turn on Anti-aliasing */
$myPicture->Antialias = TRUE;

/* Draw a customized filled circle */ 
$Color = new pColor(267,165,169);
$myPicture->drawFilledCircle(300,120,50,["Color"=>$Color,"Dash"=>TRUE,"BorderColor"=>new pColor(255,255,255)]);
$myPicture->addToImageMap("CIRCLE","300,120,50",$Color->toHTMLColor(),"Circle","My Message");

/* Draw a customized polygon */
$Color = new pColor(71,87,145);
$myPicture->drawPolygon([402,62,460,80,420,190,360,168],["Color"=>$Color,"Dash"=>TRUE,"BorderColor"=>new pColor(255,255,255)]);
$myPicture->addToImageMap("POLY","402,62,460,80,420,190,360,168",$Color->toHTMLColor(),"Polygon","My Message");

/* Turn off Anti-aliasing */
$myPicture->Antialias = FALSE;

/* Draw a customized filled rectangle */ 
$Color = new pColor(150,200,170);
$myPicture->drawFilledRectangle(20,60,210,170,["Color"=>$Color,"Dash"=>TRUE,"DashColor"=>new pColor(170,220,190),"BorderColor"=>new pColor(255,255,255)]);
$myPicture->addToImageMap("RECT","20,60,210,170",$Color->toHTMLColor(),"Box 1","Message 1");

/* Draw a customized filled rectangle */ 
$Color = new pColor(209,134,27,30);
$myPicture->drawFilledRectangle(30,30,200,200,["Color"=>$Color]);
$myPicture->addToImageMap("RECT","30,30,200,200",$Color->toHTMLColor(),"Box 2","Message 2");

/* Draw a customized filled rectangle */ 
$Color = new pColor(209,31,27,100);
$myPicture->drawFilledRectangle(480,50,650,80,["Color"=>$Color,"Surrounding"=>30]);
$myPicture->addToImageMap("RECT","480,50,650,80",$Color->toHTMLColor(),"Box 3","Message 3");

/* Draw a customized filled rectangle */ 
$Color = new pColor(209,125,27,100);
$myPicture->drawFilledRectangle(480,90,650,120,["Color"=>$Color,"Surrounding"=>30]);
$myPicture->addToImageMap("RECT","480,90,650,120",$Color->toHTMLColor(),"Box 4","Message 4");

/* Draw a customized filled rectangle */ 
$Color = new pColor(209,198,27,100);
$myPicture->drawFilledRectangle(480,130,650,160,["Color"=>$Color,"Surrounding"=>30,"Ticks"=>2]);
$myPicture->addToImageMap("RECT","480,130,650,160", $Color->toHTMLColor(),"Box 5","Message 5");

/* Draw a customized filled rectangle */ 
$Color = new pColor(134,209,27,100);
$myPicture->drawFilledRectangle(480,170,650,200,["Color"=>$Color,"Surrounding"=>30,"Ticks"=>2]);
$myPicture->addToImageMap("RECT","480,170,650,200",$Color->toHTMLColor(),"Box 6","Message 6");

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/Shapes.png");

?>
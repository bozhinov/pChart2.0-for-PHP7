<?php   
/* CAT:Drawing */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\pColor;
use pChart\pDraw;

/* Create the pChart object */
$myPicture = new pDraw(700,230);

/* Populate the pData object */
$myPicture->myData->addPoints([24,25,26,25,25],"My Serie 1");
$myPicture->myData->addPoints([80,85,84,81,82],"My Serie 2");
$myPicture->myData->addPoints([17,16,18,18,15],"My Serie 3");

$myPicture->myData->setSerieProperties("My Serie 1", ["Ticks" => 4, "Description" => "Temperature"]);
$myPicture->myData->setSerieProperties("My Serie 2", ["Weight" => 2, "Description" => "Humidity\n(in percentage)"]);

$myPicture->myData->setSerieDescription("My Serie 3","Pressure");

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,700,230,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,["Color"=>new pColor(0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"fonts/PressStart2P-Regular.ttf","FontSize"=>6]);
$myPicture->drawText(10,15,"drawLegend() - Write your chart legend",["Color"=>new pColor(255)]);

/* Write a legend box */ 
$myPicture->setFontProperties(["FontName"=>"fonts/Cairo-Regular.ttf","FontSize"=>7]);
$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);
$myPicture->drawLegend(70,60);

/* Write a legend box */ 
$myPicture->setFontProperties(["FontName"=>"fonts/Abel-Regular.ttf","FontSize"=>10,"Color"=>new pColor(30)]);
$myPicture->drawLegend(230,60,["BoxSize"=>4,"Color"=>new pColor(173,163,83),"Surrounding"=>20,"Family"=>LEGEND_FAMILY_CIRCLE]);

/* Write a legend box */ 
$myPicture->setFontProperties(["FontName"=>"fonts/Cairo-Regular.ttf","FontSize"=>9,"Color"=>new pColor(80)]);
$myPicture->drawLegend(400,60,["Style"=>LEGEND_BOX,"BoxSize"=>4,"Color"=>new pColor(200,200,200,30),"Surrounding"=>20]);

/* Write a legend box */ 
$myPicture->setFontProperties(["FontName"=>"fonts/PressStart2P-Regular.ttf","FontSize"=>6,"Color"=>new pColor(0,0,0,60)]);
$myPicture->drawLegend(70,150,["Mode"=>LEGEND_HORIZONTAL, "Family"=>LEGEND_FAMILY_CIRCLE]);

/* Write a legend box */ 
$myPicture->setFontProperties(["FontName"=>"fonts/Cairo-Regular.ttf","FontSize"=>7]);
$myPicture->drawLegend(400,150,["Style"=>LEGEND_BOX,"Mode"=>LEGEND_HORIZONTAL, "BoxWidth"=>30,"Family"=>LEGEND_FAMILY_LINE]);

/* Write a legend box */ 
$myPicture->setFontProperties(["FontSize"=>8]);
$myPicture->drawFilledRectangle(1,200,698,228,["Color"=>new pColor(255,255,255,30)]);
$myPicture->drawLegend(10,208,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Define series icons */
$myPicture->myData->setSerieProperties("My Serie 1", ["Picture" => "examples/resources/application_view_list.png"]);
$myPicture->myData->setSerieProperties("My Serie 2", ["Picture" => "examples/resources/application_view_tile.png"]);
$myPicture->myData->setSerieProperties("My Serie 3", ["Picture" => "examples/resources/chart_bar.png"]);

/* Write a legend box */ 
$myPicture->setFontProperties(["FontName"=>"fonts/Cairo-Regular.ttf","FontSize"=>7]);
$myPicture->drawLegend(540,50,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.drawLegend.png");


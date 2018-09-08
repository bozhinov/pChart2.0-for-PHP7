<?php

require_once("bootstrap.php");

use pChart\{
	pDraw,
	pColor,
	pBarcode39,
	pBarcode128
};

/* CAT:Barcode */

/* Create the pChart object */
$myPicture = new pDraw(600,310,TRUE);

/* Draw the rounded box */
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color" => new pColor(0,0,0,30)]);
$myPicture->drawRoundedFilledRectangle(10,10,590,300,10,["Color"=> new pColor(255,255,255), "BorderColor" => new pColor(0,0,0)]);

/* Draw the cell divisions */
$myPicture->setShadow(FALSE);
$Settings = ["Color" => new pColor(0,0,0)];
$myPicture->drawLine(10,110,590,110,$Settings);
$myPicture->drawLine(200,10,200,110,$Settings);
$myPicture->drawLine(400,10,400,110,$Settings);
$myPicture->drawLine(10,160,590,160,$Settings);
$myPicture->drawLine(220,160,220,200,$Settings);
$myPicture->drawLine(320,160,320,200,$Settings);
$myPicture->drawLine(10,200,590,200,$Settings);
$myPicture->drawLine(400,220,400,300,$Settings);

/* Write the fields labels */
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>10]);
$Settings = ["Color" => new pColor(0,0,0),"Align"=>TEXT_ALIGN_TOPLEFT];
$myPicture->drawText(20,20,"FROM",$Settings);
$myPicture->drawText(210,20,"TO",$Settings);
$myPicture->drawText(20,120,"ACCT.\r\nNUMBER",$Settings);
$myPicture->drawText(20,166,"QUANTITY",$Settings);
$myPicture->drawText(230,166,"SHIPMENT CODE",$Settings);
$myPicture->drawText(330,166,"SENDER CODE",$Settings);
$myPicture->drawText(410,220,"MFG DATE",$Settings);
$myPicture->drawText(410,260,"NET WEIGTH",$Settings);

/* Filling the fields values */
$myPicture->setFontProperties(["FontSize"=>16]);
$myPicture->drawText(70,20,"BEBEER INC\r\n342, MAIN STREET\r\n33000 BORDEAUX\r\nFRANCE",$Settings);
$myPicture->drawText(250,20,"MUSTAFA'S BAR\r\n18, CAPITOL STREET\r\n31000 TOULOUSE\r\nFRANCE",$Settings);

$myPicture->setFontProperties(["FontSize"=>35]);
$myPicture->drawText(100,120,"2342355552340",$Settings);

$myPicture->setFontProperties(["FontSize"=>20]);
$Settings = ["Color" => new pColor(0,0,0),"Align"=>TEXT_ALIGN_TOPRIGHT];
$myPicture->drawText(210,180,"75 CANS",$Settings);
$myPicture->drawText(310,180,"TLSE",$Settings); 
$myPicture->drawText(580,180,"WAREHOUSE#SLOT#B15",$Settings);

$Settings = ["Color" => new pColor(0,0,0),"Align" => TEXT_ALIGN_TOPLEFT];
$myPicture->drawText(410,236,"06/06/2010",$Settings);
$myPicture->drawText(410,276,"12.340 Kg",$Settings);

/* Create the barcode 39 object */ 
$Barcode39 = new pBarcode39($myPicture, FALSE, "pChart/data/39.db", $UseCache="cache/");

$Barcode39->myPicture->setFontProperties(["FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
$Barcode39->draw("12250000234502",30,220,["ShowLegend" => TRUE, "Height" => 55, "DrawArea" => FALSE]);

$Settings = ["ShowLegend" => TRUE, "Height" => 14,"DrawArea" => FALSE];
$Barcode39->draw("75 cans",260,220,$Settings);
$Barcode39->draw("06062010",260,260,$Settings);

/* Create the barcode 128 object */ 
$Barcode128 = new pBarcode128($myPicture,"pChart/data/128B.db", $UseCache="cache/");
$Barcode128->draw("TLSE",450,25,["ShowLegend" => TRUE, "Height" => 65,"DrawArea" => FALSE]);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.barcode.png");
 
?>
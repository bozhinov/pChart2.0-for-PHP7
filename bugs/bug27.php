<?php

/* pChart library inclusions */
require_once("examples\bootstrap.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

$Image_Width = 1800;
$Image_Height = 600;
$myPicture = new pDraw($Image_Width, $Image_Height);

$x_axis_points_array = [
	0 => '00', 1 => '0030', 2 => '01', 3 => '0130', 4 => '02', 5 => '0230', 6 => '03', 7 => '0330', 8 => '04', 9 => '0430', 10 =>'05', 11 =>'0530', 12 =>'06', 13 =>'0630', 14 =>'07', 15 =>'0730', 16 =>'08',
	17 =>'0830', 18 =>'09', 19 =>'0930', 20 =>'10', 21 =>'1030', 22 =>'11', 23 =>'1130', 24 =>'12', 25 =>'1230', 26 =>'13', 27 =>'1330', 28 =>'14', 29 =>'1430', 30 =>'15', 31 =>'1530', 32 =>'16',
	33 =>'1630', 34 =>'17', 35 => '1730'
];

$measurement_array = [
	0 => 20.00, 1 => 23.00, 2 => 27.00, 3 => 26.89, 4 => 26.78, 5 => 26.67, 6 => 26.56, 7 => 26.44, 8 => 26.33, 9 => 26.22, 10 => 26.11, 11 => 26.00, 12 => 21.00, 13 => 29.00, 14 => 26.00, 15 => 25.00,
	16 => 26.00, 17 => 26.00, 18 => 0.123456789, 19 => 0.123456789, 20 => 0.123456789, 21 => VOID, 22 => VOID, 23 => VOID, 24 => VOID, 25 => VOID, 26 => VOID, 27 => VOID, 28 => VOID, 29 => VOID, 30 => VOID,
	31 => VOID, 32 => VOID, 33 => VOID, 34 => VOID, 35 => VOID
];

$myPicture->myData->addPoints($x_axis_points_array,"Timestamp");
$myPicture->myData->setAbscissa("Timestamp");
$myPicture->myData->setAxisProperties(0, ["Name" => "Air Quality Index", "Unit" => " Idx"]);
$myPicture->myData->addPoints($measurement_array, 1);
$myPicture->myData->setSerieDescription(1, 'Hall');

$GraphX_UL = $Image_Width * 0.06;
$GraphY_UL = $Image_Height * 0.15;
$Graph_Width = $Image_Width * 0.8;
$Graph_Height = $Image_Height * 0.65;

$myPicture->setGraphArea($GraphX_UL, $GraphY_UL, $GraphX_UL + $Graph_Width, $GraphY_UL + $Graph_Height);

$myPicture->drawFilledRectangle(0, 0, $Image_Width, $Image_Height, ["Color"=> new pColor(170,183,87)]); // Olive-green color #AAB757
$myPicture->setFontProperties(["Color"=> new pColor(255,255,0),"FontName"=>"pChart/fonts/Cairo-Regular.ttf","FontSize"=>10]);

$myPicture->drawScale([
	"Mode"=>SCALE_MODE_MANUAL,
	"ManualScale"=>[0=>["Min"=>20,"Max"=>30]],
	"DrawSubTicks"=>TRUE,
	"DrawXLines"=>1 ,
	"DrawArrows"=>FALSE,
	"ArrowSize"=>6
]);

$XAxis_Text_X = intval($GraphX_UL + 0.5 * $Graph_Width);
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=> new pColor(50,50,50,20)]);
$myPicture->drawText($XAxis_Text_X,$Image_Height*0.84,'Time',["Align"=>TEXT_ALIGN_TOPMIDDLE, "Color"=> new pColor(255,255,0)]);

(new pCharts($myPicture))->drawSplineChart();

$myPicture->render('temp/bug27.png');

?>
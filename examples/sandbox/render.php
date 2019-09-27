<?php
/*
render.php - Sandbox rendering engine

Version     : 1.2.3
Made by     : Jean-Damien POGOLOTTI
Maintained by : Momchil Bozhinov
Last Update : 05/09/19

This file can be distributed under the license you can find at:

http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

chdir("../");

$WebConfig = json_decode($_REQUEST["Data"], TRUE, 3, JSON_INVALID_UTF8_IGNORE); // PHP 7.2+
if ((!is_null($WebConfig)) && ($WebConfig !== FALSE)){
	extract($WebConfig);
} else {
	die("Invalid data!");
}

$p_templates = [
	"autumn" => [[185,106,154,100],	[216,137,184,100],	[156,192,137,100],	[216,243,201,100],	[253,232,215,100],	[255,255,255,100]],
	"blind" => 	[[109,152,171,100],	[0,39,94,100],		[254,183,41,100],	[168,177,184,100],	[255,255,255,100],	[0,0,0,100]],
	"evening" =>[[242,245,237,100],	[255,194,0,100],	[255,91,0,100],		[184,0,40,100],		[132,0,46,100],		[74,192,242,100]],
	"kitchen" =>[[155,225,251,100],	[197,239,253,100],	[189,32,49,100],	[35,31,32,100],		[255,255,255,100],	[0,98,149,100]],
	"navy" => 	[[25,78,132,100],	[59,107,156,100],	[31,36,42,100],		[55,65,74,100],		[96,187,34,100],	[242,186,187,100]],
	"shade" => 	[[117,113,22,100],	[174,188,33,100],	[217,219,86,100],	[0,71,127,100],		[76,136,190,100],	[141,195,233,100]],
	"spring" => [[146,123,81,100],	[168,145,102,100],	[128,195,28,100],	[188,221,90,100],	[255,121,0,100],	[251,179,107,100]],
	"summer" => [[253,184,19,100],	[246,139,31,100],	[241,112,34,100],	[98,194,204,100],	[228,246,248,100],	[238,246,108,100]],
	"light" => 	[[239,210,121,100],	[149,203,233,100],	[2,71,105,100],		[175,215,117,100],	[44,87,0,100],		[222,157,127,100]]
];

/* pChart library inclusions */
require_once("../examples/bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pCharts
};

# loading the constants
$CNST = new pDraw(1, 1);
unset($CNST);

require_once("helper.class.php");
$helper = new helper();

$code = [
	'require_once("examples/bootstrap.php");',
	NULL,
	'use pChart\{pDraw,pCharts,pColor};',
	NULL
];

if ($g_transparent){
	$code[] = '$myPicture = new pDraw('.$g_width.','.$g_height.',TRUE);';
} else {
	$code[] = '$myPicture = new pDraw('.$g_width.','.$g_height.');';
}

if ($p_template != "default"){
	$code[] = NULL;
	$template = "[";
	for($i = 0; $i < 6; $i++){
		$template .= "[".$helper->stringify($p_templates[$p_template][$i])."],";
	}
	$template = substr($template, 0,-1)."]";
	$code[] = '$myPicture->myData->loadPalette('.$template.',TRUE);';
}

$Axis = [];

if ($d_serie1_enabled)
{
	$code[] = '$myPicture->myData->addPoints(['.$helper->stringify($data1).'],"Serie1");';
	$code[] = '$myPicture->myData->setSerieDescription("Serie1","'.$d_serie1_name.'");';
	$code[] = '$myPicture->myData->setSerieOnAxis("Serie1",'.$d_serie1_axis.');';
	$code[] = NULL;
	$Axis[$d_serie1_axis] = TRUE;
}

if ($d_serie2_enabled)
{
	$code[] = '$myPicture->myData->addPoints(['.$helper->stringify($data2).'],"Serie2");';
	$code[] = '$myPicture->myData->setSerieDescription("Serie2","'.$d_serie2_name.'");';
	$code[] = '$myPicture->myData->setSerieOnAxis("Serie2",'.$d_serie2_axis.');';
	$code[] = NULL;
	$Axis[$d_serie2_axis] = TRUE;
}

if ($d_serie3_enabled)
{
	$code[] = '$myPicture->myData->addPoints(['.$helper->stringify($data3).'],"Serie3");';
	$code[] = '$myPicture->myData->setSerieDescription("Serie3","'.$d_serie3_name.'");';
	$code[] = '$myPicture->myData->setSerieOnAxis("Serie3",'.$d_serie3_axis.');';
	$code[] = NULL;
	$Axis[$d_serie3_axis] = TRUE;
}

if ($d_absissa_enabled)
{
	$code[] = '$myPicture->myData->addPoints(['.$helper->stringify($absissa).'],"Absissa");';
	$code[] = '$myPicture->myData->setAbscissa("Absissa");';
	$code[] = NULL;
}

if (isset($Axis[0]))
{
	$code[] = '$myPicture->myData->setAxisPosition(0,'.$d_axis0_position.');';
	$code[] = '$myPicture->myData->setAxisName(0,"'.$d_axis0_name.'");';
	$code[] = '$myPicture->myData->setAxisUnit(0,"'.$d_axis0_unit.'");';
}

if (isset($Axis[1]))
{
	$code[] = '$myPicture->myData->setAxisPosition(1,'.$d_axis1_position.');';
	$code[] = '$myPicture->myData->setAxisName(1,"'.$d_axis1_name.'");';
	$code[] = '$myPicture->myData->setAxisUnit(1,"'.$d_axis1_unit.'");';
	$code[] = NULL;
}

if (isset($Axis[2])){
	$code[] = '$myPicture->myData->setAxisPosition(2,'.$d_axis2_position.');';
	$code[] = '$myPicture->myData->setAxisName(2,"'.$d_axis2_name.'");';
	$code[] = '$myPicture->myData->setAxisUnit(2,"'.$d_axis2_unit.'");';
	$code[] = NULL;
}

if ($d_normalize_enabled){
	$code[] = '$myPicture->myData->normalize(100);';
}

if (!$g_aa){
	$code[] = '$myPicture->setAntialias(FALSE);';
	$code[] = NULL;
}

if ($g_solid_enabled){

	$Settings = ["Color"=>$helper->hexToColorObj($g_solid_color)];

	if ($g_solid_dashed){
		$Settings["Dash"] = TRUE;

		list($R,$G,$B) = $helper->getRGB($g_solid_color);
		$extr_solid_color = new pColor($R,$G,$B);
		$extr_solid_color->RGBChange(20);
		$Settings["DashColor"] = $helper->hexToColorObj($extr_solid_color->toHex());
	}

	$code[] = $helper->dumpArray("Settings",$Settings);
	$code[] = '$myPicture->drawFilledRectangle(0,0,'.$g_width.','.$g_height.',$Settings);';
}

if ($g_gradient_enabled){
	$code[] = '$Settings = ["StartColor"=> '.$helper->hexToColorObj($g_gradient_start).',"EndColor"=> '.$helper->hexToColorObj($g_gradient_end).'];';
	$code[] = '$myPicture->drawGradientArea(0,0,'.$g_width.','.$g_height.','.$g_gradient_direction.',$Settings);';
	$code[] = NULL;
}

if($g_border){
	$code[] = '$myPicture->drawRectangle(0,0,'.($g_width-1).','.($g_height-1).',["Color"=>new pColor(0)]);';
	$code[] = NULL;
}

if($g_shadow){
	$code[] = '$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(50,50,50,20)]);';
	$code[] = NULL;
}

if ($g_title_enabled){

	$code[] = '$myPicture->setFontProperties(["FontName"=>"pChart/fonts/'.$g_title_font.'","FontSize"=>'.$g_title_font_size.']);';

	$TextSettings = ["Align"=>(int)$g_title_align,"Color"=>$helper->hexToColorObj($g_title_color)];
	if ($g_title_box){ 
		$TextSettings["DrawBox"] = TRUE;
		$TextSettings["BoxColor"] = "new pColor(255,255,255,30)";
	}

	$code[] = $helper->dumpArray("TextSettings",$TextSettings);
	$code[] = '$myPicture->drawText('.$g_title_x.','.$g_title_y.',"'.$g_title.'",$TextSettings);';
	$code[] = NULL;
}

/* Scale section */
if ($g_shadow){
	$code[] = '$myPicture->setShadow(FALSE);';
}

$code[] = '$myPicture->setGraphArea('.$s_x.','.$s_y.','.($s_x+$s_width).','.($s_y+$s_height).');';
$code[] = '$myPicture->setFontProperties(["Color"=> '.$helper->hexToColorObj($s_font_color).',"FontName"=>"pChart/fonts/'.$s_font.'","FontSize"=>'.$s_font_size.']);';
$code[] = NULL;

/* Scale specific parameters -------------------------------------------------------------------------------- */
$Settings = [
	"Pos"=>(int)$s_direction,
	"Mode"=>(int)$s_mode,
	"LabelingMethod"=>(int)$s_x_labeling,
	"GridColor"=> $helper->hexToColorObj($s_grid_color, (int)$s_grid_alpha),
	"TickColor"=> $helper->hexToColorObj($s_ticks_color, (int)$s_ticks_alpha),
	"LabelRotation"=>$s_x_label_rotation
];

($s_x_skip != 0) AND $Settings["LabelSkip"] = (int)$s_x_skip;
($s_cycle_enabled) AND $Settings["CycleBackground"] = TRUE;
($s_arrows_enabled) AND $Settings["DrawArrows"] = TRUE;
$Settings["DrawXLines"] = ($s_grid_x_enabled)? TRUE : 0;

if ($s_subticks_enabled){
	$Settings["DrawSubTicks"] = TRUE;
	$Settings["SubTickColor"] = $helper->hexToColorObj($s_subticks_color, (int)$s_subticks_alpha);
}

if (!$s_automargin_enabled){
	$Settings["XMargin"] = (int)$s_x_margin;
	$Settings["YMargin"] = (int)$s_y_margin;
}

$Settings["DrawYLines"] = ($s_grid_y_enabled) ? "ALL" : "NONE";
$code[] = $helper->dumpArray("Settings",$Settings);
$code[] = '$myPicture->drawScale($Settings);';
$code[] = NULL;

if ($g_shadow){
	$code[] = '$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(50,50,50,10)]);';
	$code[] = NULL;
}

/* Chart specific parameters -------------------------------------------------------------------------------- */
$Config = ($c_display_values) ? ["DisplayValues"=>TRUE] : [];

switch($c_family){
	case "plot":
		$Config["PlotSize"] = (int)$c_plot_size;
		if ($c_border_enabled){
			$Config["PlotBorder"] = TRUE;
			$Config["BorderSize"] = (int)$c_border_size;
		}
		$chartType = "drawPlotChart";
		break;
	case "line":
		if ($c_break){
			$Config["BreakVoid"] = 0;
			$Config["BreakColor"] = $helper->hexToColorObj($c_break_color);
		}
		$chartType = "drawLineChart";
		break;
	case "step":
		if ($c_break){
			$Config["BreakVoid"] = 0;
			$Config["BreakColor"] = $helper->hexToColorObj($c_break_color);
		}
		$chartType = "drawStepChart";
		break;
	case "spline":
		if ($c_break){
			$Config["BreakVoid"] = 0;
			$Config["BreakColor"] = $helper->hexToColorObj($c_break_color);
		}
		$chartType = "drawSplineChart";
		break;
	case "area":
		if ($c_forced_transparency){
			$Config["ForceTransparency"] = (int)$c_transparency;
		}
		if ($c_around_zero2){
			$Config["AroundZero"] = TRUE;
		}
		$chartType = "drawAreaChart";
		break;
	case "fstep":
		if ($c_forced_transparency){
			$Config["ForceTransparency"] = (int)$c_transparency;
		}
		if ($c_around_zero2){
			$Config["AroundZero"] = TRUE;
		}
		$chartType = "drawFilledStepChart";
		break;
	case "fspline":
		if ($c_forced_transparency){
			$Config["ForceTransparency"] = (int)$c_transparency;
		}
		if ($c_around_zero2){
			$Config["AroundZero"] = TRUE;
		}
		$chartType = "drawFilledSplineChart";
		break;
	case "sarea":
		if ($c_forced_transparency){
			$Config["ForceTransparency"] = (int)$c_transparency;
		}
		if ($c_around_zero2){
			$Config["AroundZero"] = TRUE;
		}
		$chartType = "drawStackedAreaChart";
		break;
	case "sbar":
		($c_bar_rounded)  AND $Config["Rounded"] = TRUE;
		($c_bar_gradient) AND $Config["Gradient"] = TRUE;
		($c_around_zero1) AND $Config["AroundZero"] = TRUE;
		$chartType = "drawStackedBarChart";
		break;
	case "bar":
		($c_bar_rounded)  AND $Config["Rounded"] = TRUE;
		($c_bar_gradient) AND $Config["Gradient"] = TRUE;
		($c_around_zero1) AND $Config["AroundZero"] = TRUE;
		$chartType = "drawBarChart";
		break;
}

$code[] = $helper->dumpArray("Config",$Config);
$code[] = '(new pCharts($myPicture))->'.$chartType.'($Config);';

if ($t_enabled){

	// myPicture obj required
	eval(implode("", $code));
	$Data = $myPicture->myData->getData();

	if (isset($Data["Axis"][$t_axis])){
		$Config = ["AxisID" => $t_axis];
	} else {
		$Config = [];
	}

	unset($Data);
	unset($myPicture);

	$Config["Color"] = $helper->hexToColorObj($t_color, (int)$t_alpha);
	$Config["Ticks"] = ($t_ticks) ? 4 : 0;

	if ($t_caption_enabled){
		$Config["WriteCaption"] = TRUE;
		$Config["Caption"] = chr(34).$t_caption.chr(34);
		if ($t_box){
			$Config["DrawBox"] = TRUE;
		}
	}

	$code[] = NULL;
	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '$myPicture->drawThreshold(['.$t_value.'],$Config);';
}

if ($l_enabled){
	// myPicture obj required to get the proper size of the legend
	eval(implode("", $code));

	$l_format = (int)$l_format;
	$l_orientation = (int)$l_orientation;
	$l_family = (int)$l_family;
	$l_margin = (int)$l_margin;

	$Config = [
		"FontColor" => $helper->hexToColorObj($l_font_color, (int)$l_alpha),
		"FontName" => "pChart/fonts/".$l_font,
		"FontSize" => (int)$l_font_size,
		"Margin" => $l_margin,
		"BoxSize" => (int)$l_box_size,
		"Style" => $l_format,
		"Mode" => $l_orientation,
		"Family" => $l_family
	];

	$Size = $myPicture->getLegendSize($Config);
	unset($myPicture);

	if ($l_position == "CORNER_TOP_RIGHT"){
		$l_y = $l_margin + 10;
		$l_x = (int)$g_width - $Size["Width"] - 10 + $l_margin;
	} elseif ($l_position == "CORNER_BOTTOM_RIGHT"){
		$l_y = (int)$g_height - $Size["Height"] - 10 + $l_margin;
		$l_x = (int)$g_width - $Size["Width"] - 10 + $l_margin;
	}

	$Config["FontName"] = chr(34)."pChart/fonts/".$l_font.chr(34);
	$code[] = NULL;
	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '$myPicture->drawLegend('.$l_x.','.$l_y.',$Config);';
}

if ($sl_enabled){

	$Config = ["CaptionMargin" => 10, "CaptionWidth" => 10];

	($sl_shaded) AND $Config["ShadedSlopeBox"] = TRUE;
	(!$sl_caption_enabled) AND $Config["Caption"] = FALSE;
	($sl_caption_line) AND $Config["CaptionLine"] = TRUE;
	$code[] = NULL;
	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawDerivative($Config);';
}

if ($_REQUEST["Action"] == "Render"){
	eval(implode("", $code));
	echo $myPicture->toBase64();
} else {
	$code[] = NULL;
	$code[] = '$myPicture->stroke();';
	$code[] = NULL;
	echo $helper->code2src($code);
}

?>
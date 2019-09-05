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

$WebConfig = json_decode($_REQUEST["Data"], TRUE);
$Mode = $_REQUEST["Action"];

extract($WebConfig);

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
require_once("../examples/functions.inc.php");
require_once("../examples/myColors.php");
require_once("../examples/bootstrap.php");

use pChart\{
	pColor,
	pColorGradient,
	pDraw,
	pCharts
};

# loading the constants
$CNST = new pDraw(100, 100);
unset($CNST);

require_once("helper.class.php");
$helper = new helper();

$code = [
	'require_once("examples/functions.inc.php");',
	'require_once("examples/myColors.php");',
	'require_once("examples/bootstrap.php");',
	NULL,
	'use pChart\{pDraw,pCharts,pColor};',
	NULL
];

if ($g_transparent == "true"){
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

if ($d_serie1_enabled == "true")
{
	$code[] = '$myPicture->myData->addPoints(['.$helper->stringify($data0).'],"Serie1");';
	$code[] = '$myPicture->myData->setSerieDescription("Serie1","'.$d_serie1_name.'");';
	$code[] = '$myPicture->myData->setSerieOnAxis("Serie1",'.$d_serie1_axis.');';

	$Axis[$d_serie1_axis] = TRUE;
}

if ($d_serie2_enabled == "true")
{
	$code[] = '$myPicture->myData->addPoints(['.$helper->stringify($data1).'],"Serie2");';
	$code[] = '$myPicture->myData->setSerieDescription("Serie2","'.$d_serie2_name.'");';
	$code[] = '$myPicture->myData->setSerieOnAxis("Serie2",'.$d_serie2_axis.');';
	$code[] = NULL;
	$Axis[$d_serie2_axis] = TRUE;
}

if ($d_serie3_enabled == "true")
{
	$code[] = '$myPicture->myData->addPoints(['.$helper->stringify($data2).'],"Serie3");';
	$code[] = '$myPicture->myData->setSerieDescription("Serie3","'.$d_serie3_name.'");';
	$code[] = '$myPicture->myData->setSerieOnAxis("Serie3",'.$d_serie3_axis.');';
	$code[] = NULL;
	$Axis[$d_serie3_axis] = TRUE;
}

if ($d_absissa_enabled == "true")
{
	$code[] = '$myPicture->myData->addPoints(['.$helper->stringify($absissa).'],"Absissa");';
	$code[] = '$myPicture->myData->setAbscissa("Absissa");';
	$code[] = NULL;
}

if (isset($Axis[0]))
{
	if ($d_axis0_position == "left"){
		$code[] = '$myPicture->myData->setAxisPosition(0,AXIS_POSITION_LEFT);';
	} else {
		$code[] = '$myPicture->myData->setAxisPosition(0,AXIS_POSITION_RIGHT);';
	}
	$code[] = '$myPicture->myData->setAxisName(0,"'.$d_axis0_name.'");';
	$code[] = '$myPicture->myData->setAxisUnit(0,"'.$d_axis0_unit.'");';
}

if (isset($Axis[1]))
{
	if ($d_axis1_position == "left"){
		$code[] = '$myPicture->myData->setAxisPosition(1,AXIS_POSITION_LEFT);';
	} else {
		$code[] = '$myPicture->myData->setAxisPosition(1,AXIS_POSITION_RIGHT);';
	}
	$code[] = '$myPicture->myData->setAxisName(1,"'.$d_axis1_name.'");';
	$code[] = '$myPicture->myData->setAxisUnit(1,"'.$d_axis1_unit.'");';
	$code[] = NULL;
}

if (isset($Axis[2])){
	if ($d_axis2_position == "left"){
		$code[] = '$myPicture->myData->setAxisPosition(2,AXIS_POSITION_LEFT);';
	} else {
		$code[] = '$myPicture->myData->setAxisPosition(2,AXIS_POSITION_RIGHT);';
	}
	$code[] = '$myPicture->myData->setAxisName(2,"'.$d_axis2_name.'");';
	$code[] = '$myPicture->myData->setAxisUnit(2,"'.$d_axis2_unit.'");';
	$code[] = NULL;
}

if ($d_normalize_enabled == "true"){
	$code[] = '$myPicture->myData->normalize(100);';
}

if ($g_aa == "false"){
	$code[] = '$myPicture->Antialias = FALSE;';
}

if ($g_solid_enabled == "true"){

	list($R,$G,$B) = $helper->extractColors($g_solid_color);
	$Settings = ["Color"=>new pColor($R,$G,$B)];

	if ($g_solid_dashed == "true"){
		$Settings["Dash"] = TRUE;
		$Settings["DashColor"] = $Settings["Color"]->newOne()->RGBChange(20);
	}

	$code[] = $helper->dumpArray("Settings",$Settings);
	$code[] = '$myPicture->drawFilledRectangle(0,0,'.$g_width.','.$g_height.',$Settings);';
}

if ($g_gradient_enabled == "true"){

	$code[] = '$Settings = ["StartColor"=> '.$helper->HexToColorObj($g_gradient_start).',"EndColor"=> '.$helper->HexToColorObj($g_gradient_end).'];';

	if ($g_gradient_direction == "vertical"){
		$code[] = '$myPicture->drawGradientArea(0,0,'.$g_width.','.$g_height.',DIRECTION_VERTICAL,$Settings);';
	} else {
		$code[] = '$myPicture->drawGradientArea(0,0,'.$g_width.','.$g_height.',DIRECTION_HORIZONTAL,$Settings);';
	}
	$code[] = NULL;
}

if($g_border == "true"){
	$code[] = '$myPicture->drawRectangle(0,0,'.($g_width-1).','.($g_height-1).',["Color"=>new pColor(0,0,0)]);';
	$code[] = NULL;
}
if($g_shadow == "true"){
	$code[] = '$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(50,50,50,20)]);';
	$code[] = NULL;
}

if ($g_title_enabled == "true"){

	$code[] = '$myPicture->setFontProperties(["FontName"=>"pChart/fonts/'.$g_title_font.'","FontSize"=>'.$g_title_font_size.']);';

	$TextSettings = ["Align"=>$helper->getConstant($g_title_align),"Color"=>$helper->HexToColorObj($g_title_color)];
	if ($g_title_box == "true"){ 
		$TextSettings["DrawBox"] = TRUE; 
		$TextSettings["BoxColor"] = new pColor(255,255,255,30);
	}

	$code[] = $helper->dumpArray("TextSettings",$TextSettings);
	$code[] = '$myPicture->drawText('.$g_title_x.','.$g_title_y.',"'.$g_title.'",$TextSettings);';
	$code[] = NULL;
}

/* Scale section */
if ($g_shadow == "true"){
	$code[] = '$myPicture->setShadow(FALSE);';
}

$code[] = '$myPicture->setGraphArea('.$s_x.','.$s_y.','.($s_x+$s_width).','.($s_y+$s_height).');';
$code[] = '$myPicture->setFontProperties(["Color"=> '.$helper->HexToColorObj($s_font_color).',"FontName"=>"pChart/fonts/'.$s_font.'","FontSize"=>'.$s_font_size.']);';
$code[] = NULL;

/* Scale specific parameters -------------------------------------------------------------------------------- */
$Pos = ($s_direction == "SCALE_POS_LEFTRIGHT") ? 690101 : 690102;
$Labeling = ($s_x_labeling == "LABELING_ALL") ? 691011 : 691012;

switch ($s_mode){
	case "SCALE_MODE_FLOATING": $iMode = 690201; break;
	case "SCALE_MODE_START0": $iMode = 690202; break;
	case "SCALE_MODE_ADDALL": $iMode = 690203; break;
	case "SCALE_MODE_ADDALL_START0": $iMode = 690204; break;
}

$Settings = [
	"Pos"=>$Pos,
	"Mode"=>$iMode,
	"LabelingMethod"=>$Labeling,
	"GridColor"=> $helper->HexToColorObj($s_grid_color, $s_grid_alpha),
	"TickColor"=> $helper->HexToColorObj($s_ticks_color, $s_ticks_alpha),
	"LabelRotation"=>$s_x_label_rotation
];

($s_x_skip != 0) AND $Settings["LabelSkip"] = $s_x_skip;
($s_cycle_enabled == "true") AND $Settings["CycleBackground"] = TRUE;
($s_arrows_enabled == "true") AND $Settings["DrawArrows"] = TRUE;
$Settings["DrawXLines"] = ($s_grid_x_enabled == "true")? TRUE : 0;

if ($s_subticks_enabled == "true"){
	$Settings["DrawSubTicks"] = TRUE;
	$Settings["SubTickColor"] = $helper->HexToColorObj($s_subticks_color, $s_subticks_alpha);
}

if ($s_automargin_enabled == "false"){
	$Settings["XMargin"] = $s_x_margin;
	$Settings["YMargin"] = $s_y_margin;
}

$Settings["DrawYLines"] = ($s_grid_y_enabled == "true") ? "ALL" : "NONE";
$code[] = $helper->dumpArray("Settings",$Settings);
$code[] = '$myPicture->drawScale($Settings);';
$code[] = NULL;

if ($g_shadow == "true"){
	$code[] = '$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(50,50,50,10)]);';
	$code[] = NULL;
}

/* Chart specific parameters -------------------------------------------------------------------------------- */
$Config = ($c_display_values == "true") ? ["DisplayValues"=>TRUE] : [];

if ($c_family == "plot"){

	$Config["PlotSize"] = $c_plot_size;
	if ($c_border_enabled == "true"){
		$Config["PlotBorder"] = TRUE;
		$Config["BorderSize"] = $c_border_size;
	}

	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawPlotChart($Config);';
	$code[] = NULL;
}

if ($c_family == "line"){

	if ($c_break == "true"){
		$Config["BreakVoid"] = 0;
		$Config["BreakColor"] = $helper->HexToColorObj($c_break_color);
	}

	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawLineChart($Config);';
}

if ($c_family == "step"){
	if ($c_break == "true"){
		$Config["BreakVoid"] = 0;
		$Config["BreakColor"] = $helper->HexToColorObj($c_break_color);
	}

	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawStepChart($Config);';
}

if ($c_family == "spline"){

	if ($c_break == "true"){
		$Config["BreakVoid"] = 0;
		$Config["BreakColor"] = $this->helper->HexToColorObj($c_break_color);
	}

	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawSplineChart($Config);';
}

if ($c_family == "bar"){

	($c_bar_rounded == "true")  AND $Config["Rounded"] = TRUE;
	($c_bar_gradient == "true") AND $Config["Gradient"] = TRUE;
	($c_around_zero1 == "true") AND $Config["AroundZero"] = TRUE;

	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawBarChart($Config);';
}

if ($c_family == "area"){

	if ($c_forced_transparency == "true"){
		$Config["ForceTransparency"] = $c_transparency;
	}
	if ($c_around_zero2 == "true"){
		$Config["AroundZero"] = TRUE;
	}

	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawAreaChart($Config);';
}

if ($c_family == "fstep"){

	if ($c_forced_transparency == "true"){
		$Config["ForceTransparency"] = $c_transparency;
	}

	$Config["AroundZero"] = ($c_around_zero2 == "true") ? TRUE : FALSE;

	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawFilledStepChart($Config);';
}

if ($c_family == "fspline"){

	if ($c_forced_transparency == "true"){
		$Config["ForceTransparency"] = $c_transparency;
	}
	if ($c_around_zero2 == "true"){ 
		$Config["AroundZero"] = TRUE;
	}

	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawFilledSplineChart($Config);';
}

if ($c_family == "sbar")
{
	($c_bar_rounded == "true")  AND $Config["Rounded"] = TRUE;
	($c_bar_gradient == "true") AND $Config["Gradient"] = TRUE;
	($c_around_zero1 == "true") AND $Config["AroundZero"] = TRUE;

	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawStackedBarChart($Config);';
}

if ($c_family == "sarea"){

	if ($c_forced_transparency == "true"){
		$Config["ForceTransparency"] = $c_transparency;
	}
	if ($c_around_zero2 == "true"){
		$Config["AroundZero"] = TRUE; 
	}

	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawStackedAreaChart($Config);';
}

if ($t_enabled == "true"){

	$Config = ["Color" => $this->helper->HexToColorObj($t_color, $t_alpha)];

	if (isset($myData->Data["Axis"][$t_axis])){
		$Config["AxisID"] = $t_axis; 
	}

	$Config["Ticks"] = ($t_ticks == "true") ? 4 : 0;

	if ($t_caption_enabled == "true"){
		$Config["WriteCaption"] = TRUE;
		$Config["Caption"] = $t_caption;
		if ($t_box == "true"){ 
			$Config["DrawBox"] = TRUE; 
		}
	}

	$Config["Caption"] = chr(34).$t_caption.chr(34);
	$code[] = NULL;
	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '$myPicture->drawThreshold(['.$t_value.'],$Config);';
}

if ($l_enabled == "true"){
	eval($helper->code4eval($code));

	$Config = [
		"FontColor" => $helper->HexToColorObj($l_font_color,$l_alpha),
		"FontName" => "pChart/fonts/".$l_font,
		"FontSize" => $l_font_size,
		"Margin" => $l_margin,
		"BoxSize" => $l_box_size
	];

	($l_format == "LEGEND_NOBORDER") AND $Config["Style"] = 690800;
	($l_format == "LEGEND_BOX") AND $Config["Style"] = 690801;
	($l_format == "LEGEND_ROUND") AND $Config["Style"] = 690802;

	($l_orientation == "LEGEND_VERTICAL") AND $Config["Mode"] = 690901;
	($l_orientation == "LEGEND_HORIZONTAL") AND $Config["Mode"] = 690902;

	($l_family == "LEGEND_FAMILY_CIRCLE") AND $Config["Family"] = 691052;
	($l_family == "LEGEND_FAMILY_LINE") AND $Config["Family"] = 691053;

	$Size = $myPicture->getLegendSize($Config);
	unset($myPicture);

	if ($l_position == "CORNER_TOP_RIGHT"){
		$l_y = $l_margin + 10;
		$l_x = $g_width - $Size["Width"] - 10 + $l_margin;
	}

	if ($l_position == "CORNER_BOTTOM_RIGHT"){
		$l_y = $g_height - $Size["Height"] - 10 + $l_margin;
		$l_x = $g_width - $Size["Width"] - 10 + $l_margin;
	}

	$Config["FontName"] = chr(34)."pChart/fonts/".$l_font.chr(34);
	$code[] = NULL;
	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '$myPicture->drawLegend('.$l_x.','.$l_y.',$Config);';
}

if ($sl_enabled == "true"){

	$Config = ["CaptionMargin" => 10, "CaptionWidth" => 10];

	($sl_shaded == "true") AND $Config["ShadedSlopeBox"] = TRUE;
	($sl_caption_enabled != "true") AND $Config["Caption"] = FALSE;
	($sl_caption_line == "true") AND $Config["CaptionLine"] = TRUE;
	$code[] = NULL;
	$code[] = $helper->dumpArray("Config",$Config);
	$code[] = '(new pCharts($myPicture))->drawDerivative($Config);';
}

if ($Mode == "Render"){
	eval($helper->code4eval($code));
	echo $myPicture->toBase64();
} else {
	$code[] = NULL;
	$code[] = '$myPicture->stroke();';
	$code[] = NULL;
	echo $helper->code2src($code);
}

?>
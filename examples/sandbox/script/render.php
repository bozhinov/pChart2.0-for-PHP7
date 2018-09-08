<?php   
/*
 render.php - Sandbox rendering engine

 Version     : 1.1.0
 Made by     : Jean-Damien POGOLOTTI
 Last Update : 18/01/11

 This file can be distributed under the license you can find at :

	http://www.pchart.net/license

 You can find the whole class documentation on the pChart web site.
*/

session_start();
chdir("../../");

$Mode = (!isset($_GET["Mode"])) ? "Render" : $_GET["Mode"];

$Constants = readConstantFile();

/* -- Retrieve General configuration items -------------------------------- */
$g_width		= $_SESSION["g_width"];
$g_height		= $_SESSION["g_height"];
$g_border		= $_SESSION["g_border"];

$g_aa			= $_SESSION["g_aa"];
$g_shadow		= $_SESSION["g_shadow"];
$g_transparent	= $_SESSION["g_transparent"];

$g_title_enabled	= $_SESSION["g_title_enabled"];
$g_title			= $_SESSION["g_title"];
$g_title_align		= $_SESSION["g_title_align"];
$g_title_x			= $_SESSION["g_title_x"];
$g_title_y			= $_SESSION["g_title_y"];
$g_title_color		= $_SESSION["g_title_color"];
$g_title_font		= $_SESSION["g_title_font"];
$g_title_font_size	= $_SESSION["g_title_font_size"];
$g_title_box		= $_SESSION["g_title_box"];

$g_solid_enabled	= $_SESSION["g_solid_enabled"];
$g_solid_color		= $_SESSION["g_solid_color"];
$g_solid_dashed		= $_SESSION["g_solid_dashed"];

$g_gradient_enabled		= $_SESSION["g_gradient_enabled"];
$g_gradient_start		= $_SESSION["g_gradient_start"];
$g_gradient_end			= $_SESSION["g_gradient_end"];
$g_gradient_direction	= $_SESSION["g_gradient_direction"];
$g_gradient_alpha		= $_SESSION["g_gradient_alpha"];
/* ------------------------------------------------------------------------ */

/* -- Retrieve Data configuration items ----------------------------------- */
$d_serie1_enabled	= $_SESSION["d_serie1_enabled"];
$d_serie2_enabled	= $_SESSION["d_serie2_enabled"];
$d_serie3_enabled	= $_SESSION["d_serie3_enabled"];
$d_absissa_enabled	= $_SESSION["d_absissa_enabled"];

$d_serie1_name		= $_SESSION["d_serie1_name"];
$d_serie2_name		= $_SESSION["d_serie2_name"];
$d_serie3_name		= $_SESSION["d_serie3_name"];

$d_serie1_axis		= $_SESSION["d_serie1_axis"];
$d_serie2_axis		= $_SESSION["d_serie2_axis"];
$d_serie3_axis		= $_SESSION["d_serie3_axis"];

$data0 				= $_SESSION["data0"];
$data1 				= $_SESSION["data1"];
$data2 				= $_SESSION["data2"];
$absissa 			= $_SESSION["absissa"];

$d_normalize_enabled = $_SESSION["d_normalize_enabled"];

$d_axis0_name		= $_SESSION["d_axis0_name"];
$d_axis1_name		= $_SESSION["d_axis1_name"];
$d_axis2_name		= $_SESSION["d_axis2_name"];

$d_axis0_unit		= $_SESSION["d_axis0_unit"];
$d_axis1_unit		= $_SESSION["d_axis1_unit"];
$d_axis2_unit		= $_SESSION["d_axis2_unit"];

$d_axis0_position	= $_SESSION["d_axis0_position"];
$d_axis1_position	= $_SESSION["d_axis1_position"];
$d_axis2_position	= $_SESSION["d_axis2_position"];

$d_axis0_format		= $_SESSION["d_axis0_format"];
$d_axis1_format		= $_SESSION["d_axis1_format"];
$d_axis2_format		= $_SESSION["d_axis2_format"];

/* ------------------------------------------------------------------------ */

/* -- Retrieve Scale configuration items ---------------------------------- */
$s_x				= $_SESSION["s_x"];
$s_y				= $_SESSION["s_y"];
$s_width			= $_SESSION["s_width"];
$s_height			= $_SESSION["s_height"];
$s_direction		= $_SESSION["s_direction"];
$s_arrows_enabled	= $_SESSION["s_arrows_enabled"];
$s_mode				= $_SESSION["s_mode"];
$s_cycle_enabled	= $_SESSION["s_cycle_enabled"];
$s_x_margin			= $_SESSION["s_x_margin"];
$s_y_margin			= $_SESSION["s_y_margin"];
$s_automargin_enabled	= $_SESSION["s_automargin_enabled"];
$s_font				= $_SESSION["s_font"];
$s_font_size		= $_SESSION["s_font_size"];
$s_font_color		= $_SESSION["s_font_color"];

$s_x_labeling		= $_SESSION["s_x_labeling"];
$s_x_skip			= $_SESSION["s_x_skip"];
$s_x_label_rotation	= $_SESSION["s_x_label_rotation"];

$s_grid_color		= $_SESSION["s_grid_color"];
$s_grid_alpha		= $_SESSION["s_grid_alpha"];
$s_grid_x_enabled	= $_SESSION["s_grid_x_enabled"];
$s_grid_y_enabled	= $_SESSION["s_grid_y_enabled"];

$s_ticks_color		= $_SESSION["s_ticks_color"];
$s_ticks_alpha		= $_SESSION["s_ticks_alpha"];
$s_subticks_color	= $_SESSION["s_subticks_color"];
$s_subticks_alpha	= $_SESSION["s_subticks_alpha"];
$s_subticks_enabled	= $_SESSION["s_subticks_enabled"];
/* ------------------------------------------------------------------------ */

/* -- Retrieve Chart configuration items ---------------------------------- */
$c_family			= $_SESSION["c_family"];
$c_display_values	= $_SESSION["c_display_values"];
$c_break_color		= $_SESSION["c_break_color"];
$c_break			= $_SESSION["c_break"];

$c_plot_size		= $_SESSION["c_plot_size"];
$c_border_size		= $_SESSION["c_border_size"];
$c_border_enabled	= $_SESSION["c_border_enabled"];

$c_bar_classic		= $_SESSION["c_bar_classic"];
$c_bar_rounded		= $_SESSION["c_bar_rounded"];
$c_bar_gradient		= $_SESSION["c_bar_gradient"];
$c_around_zero1		= $_SESSION["c_around_zero1"];

$c_transparency		= $_SESSION["c_transparency"];
$c_forced_transparency	= $_SESSION["c_forced_transparency"];
$c_around_zero2		= $_SESSION["c_around_zero2"];
/* ------------------------------------------------------------------------ */

/* -- Retrieve Legend configuration items ---------------------------------- */
$l_enabled		= $_SESSION["l_enabled"];

$l_font			= $_SESSION["l_font"];
$l_font_size	= $_SESSION["l_font_size"];
$l_font_color	= $_SESSION["l_font_color"];

$l_margin		= $_SESSION["l_margin"];
$l_alpha		= $_SESSION["l_alpha"];
$l_format		= $_SESSION["l_format"];

$l_orientation	= $_SESSION["l_orientation"];
$l_box_size		= $_SESSION["l_box_size"];

$l_position		= $_SESSION["l_position"];
$l_x			= $_SESSION["l_x"];
$l_y			= $_SESSION["l_y"];

$l_family		= $_SESSION["l_family"];
/* ------------------------------------------------------------------------ */

/* -- Retrieve Threshold configuration items ------------------------------ */
$t_enabled		= $_SESSION["t_enabled"];

$t_value		= $_SESSION["t_value"];
$t_axis			= $_SESSION["t_axis"];

$t_color		= $_SESSION["t_color"];
$t_alpha		= $_SESSION["t_alpha"];
$t_ticks		= $_SESSION["t_ticks"];

$t_caption		= $_SESSION["t_caption"];
$t_box			= $_SESSION["t_box"];
$t_caption_enabled	= $_SESSION["t_caption_enabled"];
/* ------------------------------------------------------------------------ */

/* -- Retrieve slope chart configuration items ---------------------------- */
$sl_enabled			= $_SESSION["sl_enabled"];
$sl_shaded			= $_SESSION["sl_shaded"];
$sl_caption_enabled	= $_SESSION["sl_caption_enabled"];
$sl_caption_line	= $_SESSION["sl_caption_line"];
/* ------------------------------------------------------------------------ */

/* -- Retrieve color configuration items ---------------------------------- */
$p_template	= $_SESSION["p_template"];
/* ------------------------------------------------------------------------ */


/* pChart library inclusions */
require_once("../examples/functions.inc.php");
require_once("../examples/myColors.php");
require_once("../examples/bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pCharts
};

if ($Mode == "Render"){
	if ($g_transparent == "true"){
		$myPicture = new pDraw($g_width,$g_height,TRUE);
	} else {
		$myPicture = new pDraw($g_width,$g_height);
	}
} else {
	$myPicture = new pDraw($g_width,$g_height);
	echo "&lt;?php\r\n\r\n";
	if ($Mode == "Source"){
		echo 'require_once("examples/functions.inc.php");'."\r\n";
		echo 'require_once("examples/myColors.php");'."\r\n";
		echo 'require_once("examples/bootstrap.php");'."\r\n";
		echo 'use pChart\{pDraw,pCharts,pColor};'."\r\n";
		echo "\r\n";
	}
	if ($g_transparent == "true"){
		echo '$myPicture = new pDraw('.$g_width.','.$g_height.',TRUE);'."\r\n";
	} else {
		echo '$myPicture = new pDraw('.$g_width.','.$g_height.');'."\r\n";
	}
}

if ($p_template != "default"){
	$myPicture->myData->loadPalette("pChart/palettes/".$p_template.".color",TRUE);
}

$Axis = [];

if ($d_serie1_enabled == "true"){
	
	$data0  = stripTail($data0);
	$Values = explode("!",substr($data0,1));
	foreach($Values as $key => $Value){
		($Value == "") AND $Value = "VOID"; 
		$myPicture->myData->addPoints([$Value],"Serie1");
	}

	$myPicture->myData->setSerieDescription("Serie1",$d_serie1_name);
	$myPicture->myData->setSerieOnAxis("Serie1",$d_serie1_axis);
	$Axis[$d_serie1_axis] = TRUE;

	if ($Mode == "Source"){
		
		$Data = "";
		foreach($Values as $key => $Value){
			($Value == "" || $Value == VOID) AND $Value = "VOID"; 
			$Data = $Data.",".toString($Value); 
		}
		$Data = substr($Data,1);

		echo '$myPicture->myData->addPoints(array('.$Data.'),"Serie1");'."\r\n";
		echo '$myPicture->myData->setSerieDescription("Serie1","'.$d_serie1_name.'");'."\r\n";
		echo '$myPicture->myData->setSerieOnAxis("Serie1",'.$d_serie1_axis.');'."\r\n\r\n";

		$Axis[$d_serie1_axis] = TRUE;
	}
}

if ($d_serie2_enabled == "true"){
	
	$data1  = stripTail($data1);
	$Values = explode("!",substr($data1,1));
	foreach($Values as $key => $Value){
		($Value == "") AND $Value = "VOID";
		$myPicture->myData->addPoints([$Value],"Serie2");
	}

	$myPicture->myData->setSerieDescription("Serie2",$d_serie2_name);
	$myPicture->myData->setSerieOnAxis("Serie2",$d_serie2_axis);
	$Axis[$d_serie2_axis] = TRUE;

	if ($Mode == "Source"){
		
		$Data = "";
		foreach($Values as $key => $Value){
			($Value == "") AND $Value = "VOID";
			$Data = $Data.",".toString($Value);
		}
		$Data = substr($Data,1);

		echo '$myPicture->myData->addPoints(array('.$Data.'),"Serie2");'."\r\n";
		echo '$myPicture->myData->setSerieDescription("Serie2","'.$d_serie2_name.'");'."\r\n";
		echo '$myPicture->myData->setSerieOnAxis("Serie2",'.$d_serie2_axis.');'."\r\n\r\n";

		$Axis[$d_serie2_axis] = TRUE;
	}
}

if ($d_serie3_enabled == "true"){
	
	$data2  = stripTail($data2);
	$Values = explode("!",substr($data2,1));
	foreach($Values as $key => $Value){
		($Value == "") AND $Value = "VOID";
		$myPicture->myData->addPoints([$Value],"Serie3");
	}

	$myPicture->myData->setSerieDescription("Serie3",$d_serie3_name);
	$myPicture->myData->setSerieOnAxis("Serie3",$d_serie3_axis);
	$Axis[$d_serie3_axis] = TRUE;

	if ($Mode == "Source"){
		
		$Data = "";
		foreach($Values as $key => $Value){
			($Value == "") AND $Value = "VOID";
			$Data = $Data.",".toString($Value);
		}
		$Data = substr($Data,1);

		echo '$myPicture->myData->addPoints(array('.$Data.'),"Serie3");'."\r\n";
		echo '$myPicture->myData->setSerieDescription("Serie3","'.$d_serie3_name.'");'."\r\n";
		echo '$myPicture->myData->setSerieOnAxis("Serie3",'.$d_serie3_axis.');'."\r\n\r\n";

		$Axis[$d_serie3_axis] = TRUE;
	}
}

if ($d_absissa_enabled == "true")
{
	$absissa = stripTail($absissa);
	$Values  = explode("!",substr($absissa,1));
	foreach($Values as $key => $Value){
		($Value == "") AND $Value = VOID;
		$myPicture->myData->addPoints([$Value],"Absissa");
	}

	$myPicture->myData->setAbscissa("Absissa");

	if ($Mode == "Source"){
		$Data = "";
		foreach($Values as $key => $Value){
			($Value == "") AND $Value = "VOID";
			$Data = $Data.",".toString($Value);
		}
		$Data = substr($Data,1);

		echo '$myPicture->myData->addPoints(array('.$Data.'),"Absissa");'."\r\n";
		echo '$myPicture->myData->setAbscissa("Absissa");'."\r\n\r\n";
	}
}

if (isset($Axis[0]))
{
	if ($d_axis0_position == "left"){
		$myPicture->myData->setAxisPosition(0,AXIS_POSITION_LEFT);
	} else {
		$myPicture->myData->setAxisPosition(0,AXIS_POSITION_RIGHT);
	}
	$myPicture->myData->setAxisName(0,$d_axis0_name);
	$myPicture->myData->setAxisUnit(0,$d_axis0_unit);

	($d_axis0_format == "AXIS_FORMAT_METRIC")   AND $myPicture->myData->setAxisDisplay(0,680004);
	($d_axis0_format == "AXIS_FORMAT_CURRENCY") AND $myPicture->myData->setAxisDisplay(0,680005,"$");

	if ($Mode == "Source"){
		if ($d_axis0_position == "left"){
			echo '$myPicture->myData->setAxisPosition(0,AXIS_POSITION_LEFT);'."\r\n";
		} else {
			echo '$myPicture->myData->setAxisPosition(0,AXIS_POSITION_RIGHT);'."\r\n";
		}
		echo '$myPicture->myData->setAxisName(0,"'.$d_axis0_name.'");'."\r\n";
		echo '$myPicture->myData->setAxisUnit(0,"'.$d_axis0_unit.'");'."\r\n\r\n";
	}
}

if (isset($Axis[1]))
{
	if ($d_axis1_position == "left"){
		$myPicture->myData->setAxisPosition(1,AXIS_POSITION_LEFT);
	} else {
		$myPicture->myData->setAxisPosition(1,AXIS_POSITION_RIGHT);
	}
	$myPicture->myData->setAxisName(1,$d_axis1_name);
	$myPicture->myData->setAxisUnit(1,$d_axis1_unit);

	if ($Mode == "Source"){
		if ($d_axis1_position == "left"){
			echo '$myPicture->myData->setAxisPosition(1,AXIS_POSITION_LEFT);'."\r\n";
		} else {
			echo '$myPicture->myData->setAxisPosition(1,AXIS_POSITION_RIGHT);'."\r\n";
		}
		echo '$myPicture->myData->setAxisName(1,"'.$d_axis1_name.'");'."\r\n";
		echo '$myPicture->myData->setAxisUnit(1,"'.$d_axis1_unit.'");'."\r\n\r\n";
	}
}

if (isset($Axis[2])){

	if ($d_axis2_position == "left"){
		$myPicture->myData->setAxisPosition(2,AXIS_POSITION_LEFT);
	} else {
		$myPicture->myData->setAxisPosition(2,AXIS_POSITION_RIGHT);
	}
	$myPicture->myData->setAxisName(2,$d_axis2_name);
	$myPicture->myData->setAxisUnit(2,$d_axis2_unit);

	if ($Mode == "Source"){
		if ($d_axis2_position == "left"){
			echo '$myPicture->myData->setAxisPosition(2,AXIS_POSITION_LEFT);'."\r\n";
		} else {
			echo '$myPicture->myData->setAxisPosition(2,AXIS_POSITION_RIGHT);'."\r\n";
		}
		echo '$myPicture->myData->setAxisName(2,"'.$d_axis2_name.'");'."\r\n";
		echo '$myPicture->myData->setAxisUnit(2,"'.$d_axis2_unit.'");'."\r\n\r\n";
	}
}

if ($d_normalize_enabled == "true"){

	if ($Mode == "Render"){
		$myPicture->myData->normalize(100);
	} else {
		echo '$myPicture->myData->normalize(100);'."\r\n";
	}
}

if ($g_aa == "false"){

	if ($Mode == "Render"){
		$myPicture->Antialias = FALSE;
	} else {
		echo '$myPicture->Antialias = FALSE;'."\r\n";
	}
}

if ($g_solid_enabled == "true"){

	list($R,$G,$B) = extractColors($g_solid_color);
	$Settings = ["Color"=>new pColor($R,$G,$B)];

	if ($g_solid_dashed == "true"){
		$Settings["Dash"] = TRUE;
		$Settings["DashColor"] = $Settings["Color"]->newOne()->RGBChange(20);
	}

	if ($Mode == "Render"){
		$myPicture->drawFilledRectangle(0,0,$g_width,$g_height,$Settings);
	} else {
		 echo dumpArray("Settings",$Settings);
		 echo '$myPicture->drawFilledRectangle(0,0,'.$g_width.','.$g_height.',$Settings);'."\r\n\r\n";
	}
}

if ($g_gradient_enabled == "true"){

	list($StartR,$StartG,$StartB) = extractColors($g_gradient_start);
	list($EndR,$EndG,$EndB)       = extractColors($g_gradient_end);

	$Settings = array("StartColor"=>new pColor($StartR,$StartG,$StartB,$g_gradient_alpha),"EndColor"=>new pColor($EndR,$EndG,$EndB,$g_gradient_alpha));

	if ($Mode == "Render"){
		if ($g_gradient_direction == "vertical"){
			$myPicture->drawGradientArea(0,0,$g_width,$g_height,DIRECTION_VERTICAL,$Settings);
		} else {
			$myPicture->drawGradientArea(0,0,$g_width,$g_height,DIRECTION_HORIZONTAL,$Settings);
		}
	} else {
		echo dumpArray("Settings",$Settings);

		if ($g_gradient_direction == "vertical"){
			echo '$myPicture->drawGradientArea(0,0,'.$g_width.','.$g_height.',DIRECTION_VERTICAL,$Settings);'."\r\n\r\n";
		} else {
			echo '$myPicture->drawGradientArea(0,0,'.$g_width.','.$g_height.',DIRECTION_HORIZONTAL,$Settings);'."\r\n\r\n";
		}
	}
}

if ($Mode == "Render"){
	($g_border == "true") AND $myPicture->drawRectangle(0,0,$g_width-1,$g_height-1,["Color"=>new pColor(0,0,0)]);
	($g_shadow == "true") AND $myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(50,50,50,20)]);
} else {
	if($g_border == "true"){
		echo '$myPicture->drawRectangle(0,0,'.($g_width-1).','.($g_height-1).',array("Color"=>new pColor(0,0,0)));'."\r\n\r\n";
	}
	if($g_shadow == "true"){
		echo '$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"Color"=>new pColor(50,50,50,20)));'."\r\n\r\n";
	}
}

if ($g_title_enabled == "true"){

	if ($Mode == "Render"){
		$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/".$g_title_font,"FontSize"=>$g_title_font_size));
	} else {
		echo '$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/'.$g_title_font.'","FontSize"=>'.$g_title_font_size.'));'."\r\n";
	}

	list($R,$G,$B) = extractColors($g_title_color);

	$TextSettings = array("Align"=>getTextAlignCode($g_title_align),"Color"=>new pColor($R,$G,$B));
	if ($g_title_box == "true"){ 
		$TextSettings["DrawBox"] = TRUE; 
		$TextSettings["BoxColor"] = new pColor(255,255,255,30);
	}

	if ($Mode == "Render"){
		$myPicture->drawText($g_title_x,$g_title_y,$g_title,$TextSettings);
	} else {
		echo dumpArray("TextSettings",$TextSettings);
		echo '$myPicture->drawText('.$g_title_x.','.$g_title_y.',"'.$g_title.'",$TextSettings);'."\r\n\r\n";
	}
}

/* Scale section */
if ($g_shadow == "true"){
	if ($Mode == "Render"){
		$myPicture->setShadow(FALSE); 
	} else {
		echo '$myPicture->setShadow(FALSE);'."\r\n";
	}
}

if ($Mode == "Render"){
	$myPicture->setGraphArea($s_x,$s_y,$s_x+$s_width,$s_y+$s_height);
} else {
	echo '$myPicture->setGraphArea('.$s_x.','.$s_y.','.($s_x+$s_width).','.($s_y+$s_height).');'."\r\n";
}

list($R,$G,$B) = extractColors($s_font_color);
if ($Mode == "Render"){
	$myPicture->setFontProperties(array("Color"=>new pColor($R,$G,$B),"FontName"=>"pChart/fonts/".$s_font,"FontSize"=>$s_font_size));
} else {
	echo '$myPicture->setFontProperties(array("Color"=>new pColor('.$R,$G,$B.'),"FontName"=>"pChart/fonts/'.$s_font.'","FontSize"=>'.$s_font_size.'));'."\r\n\r\n";
}

/* Scale specific parameters -------------------------------------------------------------------------------- */
list($GridR,$GridG,$GridB) = extractColors($s_grid_color);
list($TickR,$TickG,$TickB) = extractColors($s_ticks_color);
list($SubTickR,$SubTickG,$SubTickB) = extractColors($s_subticks_color);

$Pos = ($s_direction == "SCALE_POS_LEFTRIGHT") ? 690101 : 690102;
$Labeling = ($s_x_labeling == "LABELING_ALL") ? 691011 : 691012;

switch ($s_mode){
	case "SCALE_MODE_FLOATING": $iMode = 690201; break;
	case "SCALE_MODE_START0": $iMode = 690202; break;
	case "SCALE_MODE_ADDALL": $iMode = 690203; break;
	case "SCALE_MODE_ADDALL_START0": $iMode = 690204; break;
}

$Settings = array("Pos"=>$Pos,"Mode"=>$iMode,"LabelingMethod"=>$Labeling,"GridColor"=>new pColor($GridR,$GridG,$GridB,$s_grid_alpha),"TickColor"=>new pColor($TickR,$TickG,$TickB,$s_ticks_alpha),"LabelRotation"=>$s_x_label_rotation);

($s_x_skip != 0) AND $Settings["LabelSkip"] = $s_x_skip;
($s_cycle_enabled == "true") AND $Settings["CycleBackground"] = TRUE;
($s_arrows_enabled == "true") AND $Settings["DrawArrows"] = TRUE;
$Settings["DrawXLines"] = ($s_grid_x_enabled == "true")? TRUE : 0;

if ($s_subticks_enabled == "true"){
	$Settings["DrawSubTicks"] = TRUE;
	$Settings["SubTickColor"] = new pColor($SubTickR,$SubTickG,$SubTickB,$s_subticks_alpha);
}

if ($s_automargin_enabled == "false"){
	$Settings["XMargin"] = $s_x_margin;
	$Settings["YMargin"] = $s_y_margin;
}

if ($Mode == "Render"){
	$Settings["DrawYLines"] = ($s_grid_y_enabled == "true") ? ALL : NONE;
	$myPicture->drawScale($Settings);
} else {
	$Settings["DrawYLines"] = ($s_grid_y_enabled == "true") ? "ALL" : "NONE";
	echo dumpArray("Settings",$Settings);
	echo '$myPicture->drawScale($Settings);'."\r\n\r\n";
}
/* ---------------------------------------------------------------------------------------------------------- */

if ($g_shadow == "true"){
	if ($Mode == "Render"){
		$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(50,50,50,10)]); 
	} else {
		echo '$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(50,50,50,10)]);'."\r\n\r\n"; 
	}
}

/* Chart specific parameters -------------------------------------------------------------------------------- */
$Config = ($c_display_values == "true") ? ["DisplayValues"=>TRUE] : [];

if ($c_family == "plot"){
	$Config["PlotSize"] = $c_plot_size;
	if ($c_border_enabled == "true"){
		$Config["PlotBorder"] = TRUE;
		$Config["BorderSize"] = $c_border_size;
	}

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawPlotChart($Config);
	} else {
		 echo dumpArray("Config",$Config);
		 echo '(new pCharts($myPicture))->drawPlotChart($Config);'."\r\n";
	}
}

if ($c_family == "line"){
	if ($c_break == "true"){

		list($BreakR,$BreakG,$BreakB) = extractColors($c_break_color);

		$Config["BreakVoid"] = 0;
		$Config["BreakColor"] = new pColor($BreakR,$BreakG,$BreakB);
	}

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawLineChart($Config);
	} else {
		echo dumpArray("Config",$Config);
		echo '(new pCharts($myPicture))->drawLineChart($Config);'."\r\n";
	}
}

if ($c_family == "step"){
	if ($c_break == "true"){

		list($BreakR,$BreakG,$BreakB) = extractColors($c_break_color);

		$Config["BreakVoid"] = 0;
		$Config["BreakColor"] = new pColor($BreakR,$BreakG,$BreakB);
	}

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawStepChart($Config);
	} else {
		echo dumpArray("Config",$Config);
		echo '(new pCharts($myPicture))->drawStepChart($Config);'."\r\n";
	}
}

if ($c_family == "spline"){
	if ($c_break == "true"){

		list($BreakR,$BreakG,$BreakB) = extractColors($c_break_color);

		$Config["BreakVoid"] = 0;
		$Config["BreakColor"] = new pColor($BreakR,$BreakG,$BreakB);
	}

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawSplineChart($Config);
	} else {
		echo dumpArray("Config",$Config);
		echo '(new pCharts($myPicture))->drawSplineChart($Config);'."\r\n";
	}
}

if ($c_family == "bar"){
	($c_bar_rounded == "true")  AND $Config["Rounded"] = TRUE;
	($c_bar_gradient == "true") AND $Config["Gradient"] = TRUE;
	($c_around_zero1 == "true") AND $Config["AroundZero"] = TRUE;

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawBarChart($Config);
	} else {
		 echo dumpArray("Config",$Config);
		 echo '(new pCharts($myPicture))->drawBarChart($Config);'."\r\n";
	}
}

if ($c_family == "area"){
	if ($c_forced_transparency == "true"){
		$Config["ForceTransparency"] = $c_transparency;
	}
	if ($c_around_zero2 == "true"){
		$Config["AroundZero"] = TRUE;
	}

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawAreaChart($Config);
	} else {
		echo dumpArray("Config",$Config);
		echo '(new pCharts($myPicture))->drawAreaChart($Config);'."\r\n";
	}
}

if ($c_family == "fstep"){

	if ($c_forced_transparency == "true"){
		$Config["ForceTransparency"] = $c_transparency;
	}

	$Config["AroundZero"] = ($c_around_zero2 == "true") ? TRUE : FALSE;

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawFilledStepChart($Config);
	} else {
		echo dumpArray("Config",$Config);
		echo '(new pCharts($myPicture))->drawFilledStepChart($Config);'."\r\n";
	}
}

if ($c_family == "fspline"){
	
	if ($c_forced_transparency == "true"){
		$Config["ForceTransparency"] = $c_transparency;
	}
	if ($c_around_zero2 == "true"){ 
		$Config["AroundZero"] = TRUE;
	}

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawFilledSplineChart($Config);
	} else {
		echo dumpArray("Config",$Config);
		echo '(new pCharts($myPicture))->drawFilledSplineChart($Config);'."\r\n";
	}
}

if ($c_family == "sbar")
{
	($c_bar_rounded == "true")  AND $Config["Rounded"] = TRUE;
	($c_bar_gradient == "true") AND $Config["Gradient"] = TRUE;
	($c_around_zero1 == "true") AND $Config["AroundZero"] = TRUE;

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawStackedBarChart($Config);
	} else {
		echo dumpArray("Config",$Config);
		echo '(new pCharts($myPicture))->drawStackedBarChart($Config);'."\r\n";
	}
}

if ($c_family == "sarea"){

	if ($c_forced_transparency == "true"){
		$Config["ForceTransparency"] = $c_transparency;
	}
	if ($c_around_zero2 == "true"){
		$Config["AroundZero"] = TRUE; 
	}

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawStackedAreaChart($Config);
	} else	{
		echo dumpArray("Config",$Config);
		echo '(new pCharts($myPicture))->drawStackedAreaChart($Config);'."\r\n";
	}
}

if ($t_enabled == "true"){

	list($R,$G,$B) = extractColors($t_color);

	$Config = ["Color" => new pColor($R,$G,$B,$t_alpha)];

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

	if ($Mode == "Render"){
		$myPicture->drawThreshold([$t_value],$Config);
	} else {
		$Config["Caption"] = chr(34).$t_caption.chr(34);

		echo "\r\n";
		echo dumpArray("Config",$Config);
		echo '$myPicture->drawThreshold(['.$t_value.'],$Config);'."\r\n";
	}
}

if ($l_enabled == "true"){

	list($R,$G,$B) = extractColors($l_font_color);

	$Config = [
		"FontColor" => new pColor($R,$G,$B,$l_alpha),
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

	if ($l_position == "CORNER_TOP_RIGHT"){
		$l_y = $l_margin + 10;
		$l_x = $g_width - $Size["Width"] - 10 + $l_margin;
	}

	if ($l_position == "CORNER_BOTTOM_RIGHT"){
		$l_y = $g_height - $Size["Height"] - 10 + $l_margin;
		$l_x = $g_width - $Size["Width"] - 10 + $l_margin;
	}

	if ($Mode == "Render"){
		$myPicture->drawLegend($l_x,$l_y,$Config);
	} else {
		$Config["FontName"] = chr(34)."pChart/fonts/".$l_font.chr(34);

		echo "\r\n";
		echo dumpArray("Config",$Config);
		echo '$myPicture->drawLegend('.$l_x.','.$l_y.',$Config);'."\r\n";
	}
}

if ($sl_enabled == "true"){

	$Config = ["CaptionMargin" => 10, "CaptionWidth" => 10];

	($sl_shaded == "true") AND $Config["ShadedSlopeBox"] = TRUE;
	($sl_caption_enabled != "true") AND $Config["Caption"] = FALSE;
	($sl_caption_line == "true") AND $Config["CaptionLine"] = TRUE;

	if ($Mode == "Render"){
		(new pCharts($myPicture))->drawDerivative($Config);
	} else {
		echo "\r\n";
		echo dumpArray("Config",$Config);
		echo '(new pCharts($myPicture))->drawDerivative($Config);'."\r\n";
	}
}

if ($Mode == "Render"){
	$myPicture->stroke();
} else {
	echo "\r\n".'$myPicture->stroke();'."\r\n\r\n?>";
}

function extractColors($Hexa)
{
	if (strlen($Hexa) != 6){
		return [0,0,0]; 
	}

	$R = hexdec($Hexa[0].$Hexa[1]);
	$G = hexdec($Hexa[2].$Hexa[3]);
	$B = hexdec($Hexa[4].$Hexa[5]);

	return [$R,$G,$B];
}

function getTextAlignCode($Mode)
{
	switch($Mode){
		case "TEXT_ALIGN_TOPLEFT": return 690401; break;
		case "TEXT_ALIGN_TOPMIDDLE": return 690401; break;
		case "TEXT_ALIGN_TOPRIGHT": return 690403; break;
		case "TEXT_ALIGN_MIDDLELEFT": return 690404; break;
		case "TEXT_ALIGN_MIDDLEMIDDLE": return 690405; break;
		case "TEXT_ALIGN_MIDDLERIGHT": return 690406; break;
		case "TEXT_ALIGN_BOTTOMLEFT": return 690407; break;
		case "TEXT_ALIGN_BOTTOMMIDDLE": return 690408; break;
		case "TEXT_ALIGN_BOTTOMRIGHT":  return 690409; break;
	}
}

function dumpArray($Name,$Values)
{
	if ($Values == []){ 
		return '$'.$Name.' = [];'."\r\n";
	}

	$Result = '$'.$Name.' = array(';
	foreach ($Values as $Key => $Value){
		if (is_array($Value)){
			$Result .= dumpArray($Value);
		} else {
			$Result .= chr(39).$Key.chr(39).'=>'.translate($Value).', ';
		}
	}

	return substr($Result, 0, -2).");\r\n";
}

function translate($Value)
{
	global $Constants;
	if (!$Value instanceof pColor){
		return (isset($Constants[$Value])) ? $Constants[$Value] : $Value;
	} else {
		return "new pColor(".$Value->R.",".$Value->G.",".$Value->B.",".$Value->Alpha.")";
	}
}


function stripTail($Values)
{
	$Values = explode("!", substr($Values, 1));

	$Temp = [];
	$Result = [];
	
	foreach($Values as $Key => $Value){
		if ($Value == ""){ 
			$Temp[] = VOID;
		} else {
			if ($Temp != [] && $Result != []){ 
				$Result = array_merge($Result,$Temp);
			} elseif ($Temp != [] && $Result == []){
				$Result = $Temp;
			}

			$Result[] = $Value;
			$Temp = [];
		}
	}

	$Serialized = "!";
	foreach($Result as $Key => $Value){
		$Serialized .= $Value."!"; 
	}
	
	return substr($Serialized, 0, -1);
}

function readConstantFile()
{
	$Result = [];
	$buffer = file_get_contents("../examples/sandbox/includes/constants.txt");
	$buffer = explode("\n" , $buffer);
	
	foreach($buffer as $line){
		$values = explode(",",$line);
		$Result[$values[0]] = substr($values[1], 0, -1); # strip \r
	}

	return $Result;
}

function toString($Value)
{
	return (is_numeric($Value) || $Value == "VOID") ? $Value : chr(34).$Value.chr(34);
}

?>
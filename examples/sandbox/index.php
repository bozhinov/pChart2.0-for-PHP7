<?php
/*
index.php - Sandbox web front end

Version     : 1.2.3
Made by     : Jean-Damien POGOLOTTI
Maintained By : Momchil Bozhinov
Last Update : 05/09/19

This file can be distributed under the license you can find at:

http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

function listfonts($selected)
{
	$list = ["Gayathri-Regular", "Signika-Regular", "Cairo-Regular", "Dosis-Light", "PressStart2P-Regular", "Abel-Regular"];
	foreach($list as $font){
		echo "<option value='".$font.".ttf'".(($font == $selected) ? " selected='selected'" : "").">".$font."</option>";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Sandbox system</title>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	<link rel='stylesheet' type='text/css' href='style.css'/>
	<script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script type='text/javascript' src='https://rawrfl.es/jquery-drawrpalette/jquery.drawrpalette.js'></script>
	<script type='text/javascript' src='functions.js'></script>
</head>
<body>

<div class='topTable'>
	<table style='display: inline-block;'>
		<td class='topMenu' id='menu6' onclick="Do('Code')"><img src='../resources/cog.png' /> Show code</td>
		<td class='topMenu' id='menu7' onclick="Do('Render')"><img src='../resources/accept.png' /> Render picture</td>
	</table>

	<table style="float: left;">
		<td class='topMenu' id='menu1' style="background-color: #D0D0D0;"><img src='../resources/tab.png' /> General settings</td>
		<td class='topMenu' id='menu2'><img src='../resources/tab.png' /> Data</td>
		<td class='topMenu' id='menu3'><img src='../resources/tab.png' /> Scale</td>
		<td class='topMenu' id='menu4'><img src='../resources/tab.png' /> Chart</td>
		<td class='topMenu' id='menu5'><img src='../resources/tab.png' /> Legend, thresholds &amp; Misc</td>
	</table>
</div>

<div style="float: left;">

<div class="roundedCorner" id='tab1'>
	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/wrench.png' /></td>
			<td width='300'><b>General settings</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td>Width</td>
			<td><input type='text' id='g_width' value='700' onkeyup='doLayout();' /></td>
			<td>Height</td>
			<td><input type='text' id='g_height' value='230' onkeyup='doLayout();' /></td>
			<td><input type='checkbox' id='g_transparent' /></td>
			<td>Transparent background</td>
		</tr>
	</table>

	<table>
		<tr>
			<td><input type='checkbox' id='g_aa' checked='checked' /></td>
			<td>Turn on anti-aliasing</td>
			<td><input type='checkbox' id='g_shadow' checked='checked' /></td>
			<td>Enable shadow</td>
			<td><input type='checkbox' id='g_border' checked='checked' /></td>
			<td>with a border</td>
		</tr>
	</table>

	<table>
		<tr>
			<td><input type='checkbox' id='g_autopos' checked='checked' onclick='toggleAuto();' /></td>
			<td>Automatic positioning of the elements</td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
		<td width='20'><img src='../resources/comment.png' /></td>
		<td width='240'><b>Chart title</b></td>
		<td width='20'><input type='checkbox' id='g_title_enabled' checked='checked' /></td>
		<td width='38'>Enabled</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='55'>Chart Title</td>
			<td><input type='text' id='g_title' value='My first pChart project' style='width: 260px;' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='147'>Alignment method (<a class='smallLinkBlack' target='_new' href='http://wiki.pchart.net/doc.draw.text.html'>help</a>)</td>
			<td>
				<select id='g_title_align'>
					<option value='690401'>TEXT_ALIGN_TOPLEFT</option>
					<option value='690402'>TEXT_ALIGN_TOPMIDDLE</option>
					<option value='690403'>TEXT_ALIGN_TOPRIGHT</option>
					<option value='690404'>TEXT_ALIGN_MIDDLELEFT</option>
					<option selected='selected' value='690405'>TEXT_ALIGN_MIDDLEMIDDLE</option>
					<option value='690406'>TEXT_ALIGN_MIDDLERIGHT</option>
					<option value='690407'>TEXT_ALIGN_BOTTOMLEFT</option>
					<option value='690408'>TEXT_ALIGN_BOTTOMMIDDLE</option>
					<option value='690409'>TEXT_ALIGN_BOTTOMRIGHT</option>
				</select>
			</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='55'>X position &nbsp;</td>
			<td><input type='text' id='g_title_x' value='350' style="width:30px;" /></td>
			<td>&nbsp;&nbsp; Y position &nbsp;</td>
			<td><input type='text' id='g_title_y' value='25' style="width:30px;" /></td>
			<td>&nbsp;&nbsp; Color &nbsp;</td>
			<td><input type='text' class='picker' id='g_title_color' value='#FFFFFF' /></td>
			<td><input type='text' id='g_title_color_show' value='#FFFFFF' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='55'>Font name &nbsp;</td>
			<td><select id='g_title_font'><?php listfonts("Cairo-Regular"); ?></select></td>
			<td>&nbsp;&nbsp; Size &nbsp;</td>
			<td><input type='text' id='g_title_font_size' value='14' style='width: 20px;'/></td>
			<td>&nbsp;&nbsp; <input type='checkbox' id='g_title_box' /></td>
			<td>in a box</td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/paintcan.png' /></td>
			<td width='240'><b>Solid background</b></td>
			<td width='20'><input type='checkbox' id='g_solid_enabled' checked='checked' /></td>
			<td width='38'>Enabled</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='55'>Color</td>
			<td><input type='text' class='picker' id='g_solid_color' value='#AAB757' /></td>
			<td><input type='text' id='g_solid_color_show' value='#AAB757' /></td>
			<td width='20'><input type='checkbox' id='g_solid_dashed' checked='checked' /></td>
			<td width='38'>Dashed</td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/paintcan.png' /></td>
			<td width='240'><b>Gradient background</b></td>
			<td width='20'><input type='checkbox' id='g_gradient_enabled' checked='checked' /></td>
			<td width='38'>Enabled</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='55'>Start color</td>
			<td><input type='text' class='picker' id='g_gradient_start' value='#DBE78B' /></td>
			<td width='55'><input type='text' id='g_gradient_start_show' value='#DBE78B' /></td>
			<td width='54'>End color &nbsp;</td>
			<td><input type='text' class='picker' id='g_gradient_end' value='#018A44' /></td>
			<td><input type='text' id='g_gradient_end_show' value='#018A44' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='55'>Direction</td>
			<td width='75'>
				<select id='g_gradient_direction'>
					<option value='DIRECTION_VERTICAL'>Vertical</option>
					<option value='DIRECTION_HORIZONTAL'>Horizontal</option>
				</select>
			</td>
			<td width='100'>&nbsp; Alpha transparency</td>
			<td><input type='text' id='g_gradient_alpha' value='50' style='width: 20px;' /></td>
			<td>%</td>
		</tr>
	</table>
</div>

<div class="roundedCorner" id='tab2' style='display: none;'>
	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/database_table.png' /></td>
			<td width='300'><b>Dataset definition</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='46'></td>
			<td width='65' align="center">Serie 1</td>
			<td width='65' align="center">Serie 2</td>
			<td width='65' align="center">Serie 3</td>
			<td width='65' align="center">Abscissa</td>
		</tr>
		<tr>
			<td>Enabled</td>
			<td align="center"><input type='checkbox' id='d_serie1_enabled' checked='checked' onclick='checkEnabledAxis();' /></td>
			<td align="center"><input type='checkbox' id='d_serie2_enabled' checked='checked' onclick='checkEnabledAxis();' /></td>
			<td align="center"><input type='checkbox' id='d_serie3_enabled' checked='checked' onclick='checkEnabledAxis();' /></td>
			<td align="center"><input type='checkbox' id='d_absissa_enabled' checked='checked' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='46'>Name</td>
			<td width='65' align="center"><input type='text' id='d_serie1_name' value='Serie 1' style='width: 50px;' /></td>
			<td width='65' align="center"><input type='text' id='d_serie2_name' value='Serie 2' style='width: 50px;' /></td>
			<td width='65' align="center"><input type='text' id='d_serie3_name' value='Serie 3' style='width: 50px;' /></td>
			<td width='65' align="center">-</td>
		</tr>
		<tr>
			<td width='46'>Binding</td>
			<td width='65' align="center">
				<select id='d_serie1_axis' style='width: 54px;' onchange='checkEnabledAxis();'>
					<option value='0' selected='selected'>Axis 0</option>
					<option value='1'>Axis 1</option>
					<option value='2'>Axis 2</option>
				</select>
			</td>
			<td width='65' align="center">
				<select id='d_serie2_axis' style='width: 54px;' onchange='checkEnabledAxis();'>
					<option value='0' selected='selected'>Axis 0</option>
					<option value='1'>Axis 1</option>
					<option value='2'>Axis 2</option>
				</select>
			</td>
			<td width='65' align="center">
				<select id='d_serie3_axis' style='width: 54px;' onchange='checkEnabledAxis();'>
					<option value='0' selected='selected'>Axis 0</option>
					<option value='1'>Axis 1</option>
					<option value='2'>Axis 2</option>
				</select>
			</td>
			<td width='65' align="center">-</td>
		</tr>
<?php
$values = ["January", "February", "March", "April", "May", "June","July", "August"];

for($i=0; $i<8;$i++){
	echo "<tr><td>";

	if ($i == 0){
		echo "Data";
	}

	echo "</td><td align=\"center\"><input type='text' id='d_serie1_data".$i."' style='width: 50px;' /></td>
	<td align=\"center\"><input type='text' id='d_serie2_data".$i."' style='width: 50px;' /></td>
	<td align=\"center\"><input type='text' id='d_serie3_data".$i."' style='width: 50px;' /></td>
	<td align=\"center\"><input type='text' id='d_absissa_data".$i."' value='".$values[$i]."' style='width: 60px;' /></td>
	</tr>";
}
?>
	</table>

	<table>
		<tr>
			<td width='50'></td>
			<td><input type='checkbox' id='d_normalize_enabled' /></td>
			<td>normalize the datasets.</td>
			<td width='10'></td>
			<td>[ <a class='smallLinkBlack' href='#' onClick="randomize()">randomize</a> ]</td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/chart_bar_edit.png' /></td>
			<td width='300'><b>Axis position and units</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='40'></td>
			<td width='90' align="center"><b>Axis 0</b></td>
			<td width='90' align="center"><b>Axis 1</b></td>
			<td width='90' align="center"><b>Axis 2</b></td>
		</tr>
		<tr>
			<td>Name</td>
			<td align="center"><input type='text' id='d_axis0_name' value='1st axis' style='width: 76px;' /></td>
			<td align="center"><input type='text' id='d_axis1_name' value='2nd axis' style='width: 76px;' /></td>
			<td align="center"><input type='text' id='d_axis2_name' value='3rd axis' style='width: 76px;' /></td>
		</tr>
		<tr>
			<td>Unit</td>
			<td align="center"><input type='text' id='d_axis0_unit' style='width: 76px;' /></td>
			<td align="center"><input type='text' id='d_axis1_unit' style='width: 76px;' /></td>
			<td align="center"><input type='text' id='d_axis2_unit' style='width: 76px;' /></td>
		</tr>
		<tr>
			<td>Position</td>
			<td align="center">
				<select id='d_axis0_position' style='width: 80px;' onchange='checkEnabledAxis();'>
					<option value='AXIS_POSITION_LEFT' selected='selected'>Left</option>
					<option value='AXIS_POSITION_RIGHT'>Right</option>
				</select>
			</td>
			<td align="center">
				<select id='d_axis1_position' style='width: 80px;' onchange='checkEnabledAxis();'>
					<option value='AXIS_POSITION_LEFT' selected='selected'>Left</option>
					<option value='AXIS_POSITION_RIGHT'>Right</option>
				</select>
			</td>
			<td align="center">
				<select id='d_axis2_position' style='width: 80px;' onchange='checkEnabledAxis();'>
					<option value='AXIS_POSITION_LEFT' selected='selected'>Left</option>
					<option value='AXIS_POSITION_RIGHT'>Right</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Format</td>
			<td align="center">
				<select id='d_axis0_format' style='width: 80px;' onchange='checkEnabledAxis();'>
					<option selected='selected' value='AXIS_FORMAT_DEFAULT'>DEFAULT</option>
					<option value='AXIS_FORMAT_METRIC'>METRIC</option>
					<option value='AXIS_FORMAT_CURRENCY'>CURRENCY</option>
				</select>
			</td>
			<td align="center">
				<select id='d_axis1_format' style='width: 80px;' onchange='checkEnabledAxis();'>
					<option selected='selected' value='AXIS_FORMAT_DEFAULT'>DEFAULT</option>
					<option value='AXIS_FORMAT_METRIC'>METRIC</option>
					<option value='AXIS_FORMAT_CURRENCY'>CURRENCY</option>
				</select>
			</td>
			<td align="center">
				<select id='d_axis2_format' style='width: 80px;' onchange='checkEnabledAxis();'>
					<option selected='selected' value='AXIS_FORMAT_DEFAULT'>DEFAULT</option>
					<option value='AXIS_FORMAT_METRIC'>METRIC</option>
					<option value='AXIS_FORMAT_CURRENCY'>CURRENCY</option>
				</select>
			</td>
		</tr>
	</table>
</div>

<div class="roundedCorner" id='tab3' style='display: none;'>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/layout_edit.png' /></td>
			<td width='300'><b>Charting area definition</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>X &nbsp;</td>
			<td><input type='text' id='s_x' value='70' style='width: 30px;' /></td>
			<td>&nbsp;&nbsp; Y &nbsp;</td>
			<td><input type='text' id='s_y' value='50' style='width: 30px;' /></td>
			<td>&nbsp;&nbsp; Width &nbsp;</td>
			<td><input type='text' id='s_width' value='590' style='width: 30px;' /></td>
			<td>&nbsp;&nbsp; Height &nbsp;</td>
			<td><input type='text' id='s_height' value='140' style='width: 30px;' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Direction</td>
			<td width='160'>
				<select id='s_direction' onchange='checkEnabledAxis();'>
					<option value='690101'>SCALE_POS_LEFTRIGHT</option>
					<option value='690102'>SCALE_POS_TOPBOTTOM</option>
				</select>
			</td>
			<td>&nbsp;<input type='checkbox' id='s_arrows_enabled' /></td>
			<td>&nbsp;with arrows</td>
		</tr>
	</table>

	<table>
		<tr>
		<td width='50'>Mode</td>
		<td width='160'>
			<select id='s_mode'>
				<option value='690201'>SCALE_MODE_FLOATING</option>
				<option value='690202'>SCALE_MODE_START0</option>
				<option value='690203'>SCALE_MODE_ADDALL</option>
				<option value='690204'>SCALE_MODE_ADDALL_START0</option>
			</select>
		</td>
		<td>&nbsp;<input type='checkbox' id='s_cycle_enabled' checked='checked' /></td>
		<td>&nbsp;Background</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>X Margin</td>
			<td width='35'><input type='text' id='s_x_margin' value='0' style='width: 30px;' /></td>
			<td width='50'>&nbsp; Y Margin</td>
			<td width='68'><input type='text' id='s_y_margin' value='0' style='width: 30px;' /></td>
			<td>&nbsp;&nbsp;<input type='checkbox' id='s_automargin_enabled' checked='checked' onclick='toggleAutoMargins();' /></td>
			<td>&nbsp;automatic</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Font</td>
			<td><select id='s_font'><?php listfonts("Cairo-Regular"); ?></select></td>
			<td>&nbsp; Size &nbsp;</td>
			<td><input type='text' id='s_font_size' value='7' style='width: 20px;' /></td>
			<td>&nbsp; Color &nbsp;</td>
			<td><input type='text' id='s_font_color' class='picker' value='#000000' /></td>
			<td><input type='text' id='s_font_color_show' value='#000000' /></td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/page_edit.png' /></td>
			<td width='300'><b>X Axis configuration</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Mode</td>
			<td>
				<select id='s_x_labeling'>
					<option value='691011'>LABELING_ALL</option>
					<option value='691012'>LABELING_DIFFERENT</option>
				</select>
			</td>
			<td>&nbsp;&nbsp; Skip each</td>
			<td>&nbsp;<input type='text' id='s_x_skip' value='0' style='width: 20px;' /></td>
			<td>&nbsp;labels</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Rotation</td>
			<td><input type='text' id='s_x_label_rotation' value='0' /></td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/page_edit.png' /></td>
			<td width='300'><b>Grid</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='70'>Grid color</td>
			<td><input type='text' id='s_grid_color' class='picker' value='#FFFFFF' /></td>
			<td><input type='text' id='s_grid_color_show' value='#FFFFFF' /></td>
			<td>&nbsp; Alpha</td>
			<td>&nbsp; <input type='text' id='s_grid_alpha' value='50' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='70'>Display</td>
			<td>&nbsp;<input type='checkbox' id='s_grid_x_enabled' checked='checked' /></td>
			<td>&nbsp;X Lines</td>
			<td>&nbsp;&nbsp;&nbsp;<input type='checkbox' id='s_grid_y_enabled' checked='checked' /></td>
			<td>&nbsp;Y Lines</td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/page_edit.png' /></td>
			<td width='300'><b>Ticks</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='70'>Ticks color</td>
			<td><input type='text' id='s_ticks_color' class='picker' value='#000000' /></td>
			<td><input type='text'  id='s_ticks_color_show' value='#000000' /></td>
			<td>&nbsp; Alpha</td>
			<td>&nbsp; <input type='text' id='s_ticks_alpha' value='50' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='70'>Sub ticks color</td>
			<td><input type='text' id='s_subticks_color' class='picker' value='#FF0000' /></td>
			<td><input type='text' id='s_subticks_color_show' value='#FF0000' /></td>
			<td>&nbsp; Alpha</td>
			<td>&nbsp; <input type='text' id='s_subticks_alpha' value='50' /></td>
			<td>&nbsp;<input type='checkbox' id='s_subticks_enabled' checked='checked' onclick='toggleSubTicks();' /></td>
			<td>&nbsp;enabled</td>
		</tr>
	</table>
</div>

<div class="roundedCorner" id='tab4' style='display: none;'>
	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/wrench.png' /></td>
			<td width='300'><b>Chart</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='60'>Chart family</td>
			<td>
				<select id='c_family' onchange='checkChartSettings();'>
					<option value='plot'>Plot chart</option>
					<option value='line'>Line chart</option>
					<option value='spline' selected='selected'>Spline chart</option>
					<option value='step'>Step chart</option>
					<option value='bar'>Bar chart</option>
					<option value='area'>Area chart</option>
					<option value='fspline'>Filled spline chart &nbsp;&nbsp;&nbsp;&nbsp;</option>
					<option value='fstep'>Filled step chart</option>
					<option value='sbar'>Stacked bar chart</option>
					<option value='sarea'>Stacked area chart</option>
				</select>
			</td>
			<td>&nbsp;Break color</td>
			<td>&nbsp;<input type='text' id='c_break_color' class='picker' value='#EA371A' /></td>
			<td><input type='text' id='c_break_color_show' value='#EA371A' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='60'>Settings : </td>
			<td><input type='checkbox' id='c_display_values' /></td>
			<td>&nbsp;Display values</td>
			<td>&nbsp;<input type='checkbox' id='c_break' /></td>
			<td>&nbsp;Don't break on VOID</td>
		</tr>
	</table>

	<div style='background: #D2F5C1; padding: 4px; color: #667309; margin-top: 10px;'>
		<table>
			<tr>
				<td width='20'><img src='../resources/comment.png' /></td>
				<td>Selecting a chart layout will enable/disable chart specifics options.</td>
			</tr>
		</table>
	</div>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/chart_line.png' /></td>
			<td width='300'><b>Plot specifics</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='60'>Plot size</td>
			<td><input type='text' id='c_plot_size' value='3' style='width: 20px;' /></td>
			<td width='60'>&nbsp;&nbsp; Border size</td>
			<td>&nbsp;<input type='text' id='c_border_size' value='2' style='width: 20px;' /></td>
			<td>&nbsp;<input type='checkbox' id='c_border_enabled' checked='checked' onclick='checkPlotBorder();' /></td>
			<td>&nbsp;border enabled</td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/chart_bar.png' /></td>
			<td width='300'><b>Bar charts specifics</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td>&nbsp;<input type='radio' id='c_bar_classic' name='c_bar_design' value='0' checked='checked' /></td>
			<td>&nbsp;Classic</td>
			<td>&nbsp;<input type='radio' id='c_bar_rounded' name='c_bar_design' value='1' /></td>
			<td>&nbsp;Rounded</td>
			<td>&nbsp;<input type='radio' id='c_bar_gradient' name='c_bar_design' value='2' /></td>
			<td>&nbsp;Gradient filling</td>
			<td>&nbsp;<input type='checkbox' id='c_around_zero1' checked='checked' /></td>
			<td>&nbsp;around zero</td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/chart_curve.png' /></td>
			<td width='300'><b>Area charts specifics</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='100'>Forced transparency</td>
			<td><input type='text' id='c_transparency' value='50' style='width: 20px;' /></td>
			<td>&nbsp;<input type='checkbox' id='c_forced_transparency' checked='checked' onclick='checkAreaChart();' /></td>
			<td>&nbsp;enabled</td>
			<td>&nbsp;<input type='checkbox' id='c_around_zero2' checked='checked' /></td>
			<td>&nbsp;wrapped around zero</td>
		</tr>
	</table>
</div>

<div class="roundedCorner" id='tab5' style='display: none;'>
	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/application_form.png' /></td>
			<td width='240'><b>Legend</b></td>
			<td width='20'><input type='checkbox' id='l_enabled' checked='checked' /></td>
			<td width='38'>Enabled</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Font</td>
			<td><select id='l_font'><?php listfonts("Cairo-Regular"); ?></select></td>
			<td>&nbsp; Size &nbsp;</td>
			<td><input type='text' id='l_font_size' value='7' style='width: 20px;' /></td>
			<td>&nbsp; Color &nbsp;</td>
			<td><input type='text' id='l_font_color' class='picker' value='#000000' /></td>
			<td><input type='text' id='l_font_color_show' value='#000000' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Margin</td>
			<td><input type='text' id='l_margin' value='6' style='width: 20px;' /></td>
			<td>&nbsp; Alpha &nbsp;</td>
			<td><input type='text' id='l_alpha' value='30' style='width: 20px;' /></td>
			<td>&nbsp; Format</td>
			<td>&nbsp; 
				<select id='l_format'>
					<option value='690800' selected='selected'>LEGEND_NOBORDER</option>
					<option value='690801'>LEGEND_BOX</option>
					<option value='690802'>LEGEND_ROUND</option>
				</select>
			</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Orientation</td>
			<td>&nbsp; 
				<select id='l_orientation' style='width: 160px;'>
					<option value='690901'>LEGEND_VERTICAL</option>
					<option value='690902' selected='selected'>LEGEND_HORIZONTAL</option>
				</select>
			</td>
			<td>&nbsp; Box size &nbsp;</td>
			<td><input type='text' id='l_box_size' value='5' style='width: 20px;' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Position</td>
			<td>&nbsp; 
				<select id='l_position' style='width: 160px;' onclick='checkLegend();'>
					<option value='CORNER_TOP_RIGHT'>CORNER_TOP_RIGHT</option>
					<option value='CORNER_BOTTOM_RIGHT'>CORNER_BOTTOM_RIGHT</option>
					<option value='Manual'>Manual</option>
				</select>
			</td>
			<td>&nbsp; X &nbsp;</td>
			<td><input type='text' id='l_x' value='10' style='width: 20px;' /></td>
			<td>&nbsp; Y &nbsp;</td>
			<td><input type='text' id='l_y' value='10' style='width: 20px;' /></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Layout</td>
			<td>&nbsp; 
				<select id='l_family' style='width: 160px;'>
					<option value='691051'>LEGEND_FAMILY_BOX</option>
					<option value='691052'>LEGEND_FAMILY_CIRCLE</option>
					<option value='691053'>LEGEND_FAMILY_LINE</option>
				</select>
			</td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/vector.png' /></td>
			<td width='240'><b>Threshold</b></td>
			<td width='20'><input type='checkbox' id='t_enabled' /></td>
			<td width='38'>Enabled</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Value</td>
			<td width='60'><input type='text' id='t_value' value='0' /></td>
			<td>&nbsp;<input type='radio' id='t_axis0' name='t_axis' value='0' checked='checked' /></td>
			<td>&nbsp;Axis 0</td>
			<td>&nbsp;<input type='radio' id='t_axis1' name='t_axis' value='1' /></td>
			<td>&nbsp;Axis 1</td>
			<td>&nbsp;<input type='radio' id='t_axis2' name='t_axis' value='2' /></td>
			<td>&nbsp;Axis 2</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Color</td>
			<td><input type='text' id='t_color' class='picker' value='#000000' /></td>
			<td><input type='text' id='t_color_show' value='#000000' /></td>
			<td>&nbsp; Alpha &nbsp;</td>
			<td><input type='text' id='t_alpha' value='50' style='width: 20px;' /></td>
			<td>&nbsp;&nbsp; <input type='checkbox' id='t_ticks' checked='checked' /></td>
			<td>&nbsp; ticks &nbsp;</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Caption</td>
			<td><input type='text' id='t_caption' value='Threshold' style='width: 50px;' /></td>
			<td>&nbsp; <input type='checkbox' id='t_box' checked='checked' /></td>
			<td>&nbsp;in a box&nbsp;</td>
			<td>&nbsp; <input type='checkbox' id='t_caption_enabled' checked='checked' /></td>
			<td>&nbsp;caption enabled &nbsp;</td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/shape_flip_vertical.png' /></td>
			<td width='240'><b>Slope chart</b></td>
			<td width='20'><input type='checkbox' id='sl_enabled' onclick='doLayout();' /></td>
			<td width='38'>Enabled</td>
		</tr>
	</table>

	<table>
		<tr>
			<td>&nbsp; <input type='checkbox' id='sl_shaded' checked='checked' /></td>
			<td>&nbsp;Shaded&nbsp;</td>
			<td>&nbsp; <input type='checkbox' id='sl_caption_enabled' checked='checked' /></td>
			<td>&nbsp;With caption &nbsp;</td>
			<td>&nbsp; <input type='checkbox' id='sl_caption_line' checked='checked' /></td>
			<td>&nbsp;Use line as caption &nbsp;</td>
		</tr>
	</table>

	<table class="defaultTable">
		<tr>
			<td width='20'><img src='../resources/color_swatch.png' /></td>
			<td width='300'><b>Palette</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width='50'>Template</td>
			<td>&nbsp; 
				<select id='p_template'>
					<option value='default'>Default</option>
					<option value='autumn'>Autumn</option>
					<option value='blind'>Blind</option>
					<option value='evening'>Evening</option>
					<option value='kitchen'>Kitchen</option>
					<option value='light'>Light</option>
					<option value='navy'>Navy</option>
					<option value='shade'>Shade</option>
					<option value='spring'>Spring</option>
					<option value='shade'>Shade</option>
					<option value='summer'>Summer</option>
				</select>
			</td>
		</tr>
	</table>

	</div>

</div>

<div style="margin-left: 400px; margin-top: 30px; width: 750px" id='result_area'></div>

<script type="text/javascript">
	$(document).ready(function() {

		CurrentDiv = "menu1";
		Automatic  = true;

		/* Initial layout */
		randomize();
		checkEnabledAxis();
		toggleSubTicks();
		toggleAutoMargins();
		checkChartSettings();
		checkLegend();

		$(".picker").drawrpalette().on("choose.drawrpalette", function(event,hexcolor){
			divID = "#" + ($(this)[0].id) + "_show";
			$(divID).val(hexcolor);
		});

		$(".picker").css({"height" : "20px", "width" : "20px"});

		$('input[type=text]').hover(
			// hover begin (mouse-in)
			function () {
				$(this).css({"border-color": "#00AAFF"});
			},
			// hover end (mouse-out)
			function () {
				$(this).css({"border-color": ""});
			}
		);

		$('td.topMenu').hover(
			function () {
				$(this).css({"background-color": "#F4F4F4"});
			},
			function () {
				if ($(this)[0].id != CurrentDiv){
					$(this).css({"background-color": "#EAEAEA"});
				} else {
					$(this).css({"background-color": "#D0D0D0"});
				}
			}
		);

		$('td.topMenu').on("click", function() {
			CurrentDiv = $(this)[0].id;
			ID = CurrentDiv.replace("menu", "");

			if (ID < 6){
				for (i=1;i<6;i++){
					if ( i != ID ){
						document.getElementById("tab"+i).style.display = "none";
						document.getElementById("menu"+i).style.backgroundColor = "#EAEAEA";
					}
				}
				document.getElementById("tab"+ID).style.display = "block";
				document.getElementById(CurrentDiv).style.backgroundColor = "#D0D0D0";
			}
		});
	});
</script>
</body>
</html>
/*
functions.js - Sandbox JS

Version     : 1.2.2
Made by     : Jean-Damien POGOLOTTI
Maintainedby: Momchil Bozhinov
Last Update : 02/09/19
This file can be distributed under the license you can find at :

			http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

Action = "Render";

function toggleAuto()
{
	Automatic = (document.getElementById("g_autopos").checked ? true : false);
}

function doLayout()
{
	if ( !Automatic ) { return; }

	g_width  = document.getElementById("g_width").value;
	g_height = document.getElementById("g_height").value;

	document.getElementById("g_title_x").value = g_width/2;

	checkEnabledAxis();
}

/* Set or remove the focus */
function setFocus(Object,Mode)
{
	Object.style.borderColor = (Mode == true ? "#808080" : "#D0D0D0");
}

function highlightDIV(ID)
{
	if ( ID == CurrentDiv ) { return; }
	document.getElementById("menu"+ID).style.backgroundColor = "#F4F4F4";
}

function clearDIV(ID)
{
	if ( ID == CurrentDiv ) { return; }
	document.getElementById("menu"+ID).style.backgroundColor = "#EAEAEA";
}

function toggleDIV(ID)
{
	/* reset the tab styles */
	for (i=1;i<=6;i++){
		if ( i != ID ) {
			document.getElementById("menu"+i).style.backgroundColor = "#EAEAEA";
			document.getElementById("menu"+i).style.borderColor = "#FEFEFE";
		}
	}

	/* hide the currently displayed tab */
	if ( CurrentDiv != ID ) {
		document.getElementById("tab"+CurrentDiv).style.display = "none";
	}

	document.getElementById("tab"+ID).style.display = "block";

	CurrentDiv = ID;
	document.getElementById("menu"+ID).style.backgroundColor = "#D0D0D0";
	document.getElementById("menu"+ID).style.borderColor = "#B0B0B0";
}

function render()
{
	Action = "Render";
	saveToSession();
}

function code()
{
	Action = "Code";
	saveToSession();
}

function saveToSession()
{
	saveGeneral();
}

function saveGeneral()
{
	$("result_area").html("<img src='graphix/wait.gif' /><br />Saving configuration (General)");

	// checkboxes & radios  - g_border, g_aa, g_shadow, g_transparent, g_autopos, g_title_enabled, g_title_box, g_solid_enabled, g_solid_dashed, g_gradient_enabled
	// text - g_width, g_height, g_title, g_title_x, g_title_y, g_title_color, g_title_font_size, g_solid_color, g_gradient_start, g_gradient_end, g_gradient_alpha
	// selects - g_title_align, g_title_font, g_gradient_direction

	GET = input4GET('g_'); 
	GET += selected4GET("g_");

	push("script/session.php?" + GET.slice(0, -1), 1);
}

function saveData()
{
	$("result_area").html("<img src='graphix/wait.gif' /><br />Saving configuration (Data)");

	// checkboxes & radios  - d_normalize_enabled
	// text - d_serie1_enabled, d_serie2_enabled, d_serie3_enabled, d_absissa_enabled, d_serie1_name, d_serie2_name, d_serie3_name, d_axis0_name, d_axis1_name, d_axis2_name, d_axis0_unit, d_axis1_unit, d_axis2_unit
	// selects - d_serie1_axis, d_serie2_axis, d_serie3_axis, d_axis0_position, d_axis1_position, d_axis2_position, d_axis0_format, d_axis1_format, d_axis2_format

	GET = input4GET('d_');
	GET += selected4GET("d_");

	data0 = [];
	data1 = [];
	data2 = [];
	absissa = [];

	for(i=0;i<8;i++)
	{
		data0.push(document.getElementById("d_serie1_data"+i).value);
		data1.push(document.getElementById("d_serie2_data"+i).value);
		data2.push(document.getElementById("d_serie3_data"+i).value);
		absissa.push(document.getElementById("d_absissa_data"+i).value);
	}

	data0 = JSON.stringify(data0);
	data1 = JSON.stringify(data1);
	data2 = JSON.stringify(data2);
	absissa = JSON.stringify(absissa);

	GET += "data0=" + data0 + "&data1=" + data1 + "&data2=" + data2 + "&absissa=" + absissa;

	push("script/session.php?" + GET, 2);
}

function saveScale()
{
	$("result_area").html("<img src='graphix/wait.gif' /><br />Saving configuration (Scale)");

	// checkboxes & radios  - s_arrows_enabled, s_cycle_enabled, s_automargin_enabled, s_grid_x_enabled, s_grid_y_enabled, s_subticks_enabled	
	// text - s_x, s_y, s_width, s_height, s_x_margin, s_y_margin, s_font_size, s_font_color, s_x_skip, s_x_label_rotation, s_grid_color, s_grid_alpha, s_ticks_color, s_ticks_alpha, s_subticks_color, s_subticks_alpha
	// selects - s_direction, s_mode, s_font, s_x_labeling

	GET = input4GET('s_'); 
	GET += selected4GET('s_');

	push("script/session.php?" + GET.slice(0, -1), 3);
}

function input4GET(key)
{
	var List = "";

	let inputs = document.querySelectorAll('input');

	inputs.forEach(function(element) {
		if ((element.id).startsWith(key)){
			if ((element.type == "checkbox") || (element.type == "radio")){
				List += element.id + "="+ element.checked+"&";
			} else if (element.type == "text"){
				List += element.id + "="+ element.value+"&";
			}
		}
	});

	return List;
}

function selected4GET(key)
{
	var List = "";

	let inputs = document.querySelectorAll('select');

	inputs.forEach(function(element) {
		if ((element.id).startsWith(key)){
			var e = document.getElementById(element.id);
			List += element.id + "=" + e.options[e.selectedIndex].value+"&";
		}
	});

	return List;
}

function getSelected(ID)
{
	e = document.getElementById(ID);
	return e.options[e.selectedIndex].value;
}

function saveChart()
{
	$("result_area").html("<img src='graphix/wait.gif' /><br />Saving configuration (Chart)");

	// checkboxes - c_display_values, c_break, c_border_enabled, c_around_zero1, c_forced_transparency, c_around_zero2	
	// radios - c_bar_classic, c_bar_rounded, c_bar_gradient
	// text - c_break_color, c_plot_size, c_border_size, c_transparency
	// selects - c_family

	GET = input4GET('c_'); 
	GET += selected4GET("c_");

	push("script/session.php?" + GET.slice(0, -1), 4);
}

function saveLegend()
{
	$("result_area").html("<img src='graphix/wait.gif' /><br />Saving configuration (Legend and Thresholds)");

	// checkboxes - l_enabled, t_enabled, t_ticks, t_box, t_caption_enabled, sl_enabled, sl_shaded, sl_caption_enabled, sl_caption_line
	// radios - c_bar_classic, c_bar_rounded, c_bar_gradient
	// text - l_font_size, l_font_color, l_margin, l_alpha, l_box_size, t_value, t_color, t_alpha, t_caption
	// selects - l_font, l_format, l_orientation, l_position, l_family, p_template

	GET = input4GET(''); 
	GET += selected4GET("l_");
	GET += selected4GET("p_");
	
	switch(true) {
		case document.getElementById("t_axis0").checked:
			t_axis = 0;
			break;
		case document.getElementById("t_axis1").checked:
			t_axis = 1;
			break;
		case document.getElementById("t_axis2").checked:
			t_axis = 2;
			break;
	}

	GET += "t_axis=" + t_axis;

	push("script/session.php?" + GET, 5);
}

function randomize()
{
	for(i=0;i<8;i++)
	{
		document.getElementById("d_serie1_data"+i).value = Math.ceil(Math.random()*100-50);
		document.getElementById("d_serie2_data"+i).value = Math.ceil(Math.random()*100-50);
		document.getElementById("d_serie3_data"+i).value = Math.ceil(Math.random()*100-50);
	}
}

function setColors()
{
	applyColor("g_title_color");
	applyColor("g_solid_color");
	applyColor("g_gradient_start");
	applyColor("g_gradient_end");
	applyColor("s_font_color");
	applyColor("s_grid_color");
	applyColor("s_ticks_color");
	applyColor("s_subticks_color");
	applyColor("l_font_color");
	applyColor("t_color");
	applyColor("c_break_color");
}

function applyColor(SourceID)
{
	TargetID = SourceID + "_show";
	color = document.getElementById(SourceID).value;
	document.getElementById(TargetID).style.backgroundColor = "#"+color.replace("#","");
}

function checkChartSettings()
{
	ChartFamily = getSelected("c_family");

	disableItem("c_plot_size");
	disableItem("c_border_size");
	disableCheck("c_border_enabled");
	disableRadio("c_bar_classic");
	disableRadio("c_bar_rounded");
	disableRadio("c_bar_gradient");
	disableCheck("c_around_zero1");
	disableItem("c_transparency");
	disableCheck("c_forced_transparency");
	disableCheck("c_around_zero2");

	switch( ChartFamily ) {
		case "plot":
			enableItem("c_plot_size");
			enableItem("c_border_size");
			enableCheck("c_border_enabled");
			checkPlotBorder();
			break;
		case "bar":
		case "sbar":
			enableRadio("c_bar_classic");
			enableRadio("c_bar_rounded");
			enableRadio("c_bar_gradient");
			enableCheck("c_around_zero1");
			break;
		case "fspline":
		case "area":
		case "sarea":
		case "fstep":
			enableItem("c_transparency");
			enableCheck("c_forced_transparency");
			enableCheck("c_around_zero2");
			checkAreaChart();
			break;
	}

	if ( Automatic )
	{
		if ( ChartFamily == "sbar" || ChartFamily == "sarea" ){
			document.getElementById("s_mode").value = "SCALE_MODE_ADDALL";
		}
	} else {
		document.getElementById("s_mode").value = "SCALE_MODE_FLOATING";
	}
}

function checkLegend()
{
	if ( getSelected("l_position") == "Manual" ) {
		enableItem("l_x");
		enableItem("l_y");
	} else {
		disableItem("l_x");
		disableItem("l_y");
	}
}

function checkPlotBorder()
{
	if ( document.getElementById("c_border_enabled").checked ) {
		enableItem("c_border_size");
	} else {
		disableItem("c_border_size");
	}
}

function checkAreaChart()
{
	if ( document.getElementById("c_forced_transparency").checked ) {
		enableItem("c_transparency");
	} else {
		disableItem("c_transparency");
	}
}

function toggleSubTicks()
{
	if ( !document.getElementById("s_subticks_enabled").checked ) {
		disableItem("s_subticks_color");
		disableItem("s_subticks_alpha");
	} else {
		enableItem("s_subticks_color");
		enableItem("s_subticks_alpha");
	}
}

function toggleAutoMargins()
{
	if ( document.getElementById("s_automargin_enabled").checked ) {
		disableItem("s_x_margin");
		disableItem("s_y_margin");
	} else {
		enableItem("s_x_margin");
		enableItem("s_y_margin");
	}
}

function checkEnabledAxis()
{
	Serie1Enabled = document.getElementById("d_serie1_enabled").checked;
	Serie2Enabled = document.getElementById("d_serie2_enabled").checked;
	Serie3Enabled = document.getElementById("d_serie3_enabled").checked;
	Serie1Binding = getSelected("d_serie1_axis");
	Serie2Binding = getSelected("d_serie2_axis");
	Serie3Binding = getSelected("d_serie3_axis");

	Series = 0;
	if ( Serie1Enabled ) Series++;
	if ( Serie2Enabled ) Series++;
	if ( Serie3Enabled ) Series++;

	if ( (Serie1Binding != 0 || !Serie1Enabled) && (Serie2Binding != 0 || !Serie2Enabled) && (Serie3Binding != 0 || !Serie3Enabled) )
	{
		disableItem("d_axis0_name");
		disableItem("d_axis0_unit");
		disableItem("d_axis0_position");
		disableItem("d_axis0_format");
	}
	else
	{
		enableItem("d_axis0_name");
		enableItem("d_axis0_unit");
		enableItem("d_axis0_position");
		enableItem("d_axis0_format");
	}

	if ( (Serie1Binding != 1 || !Serie1Enabled) && (Serie2Binding != 1 || !Serie2Enabled) && (Serie3Binding != 1 || !Serie3Enabled) )
	{
		disableItem("d_axis1_name");
		disableItem("d_axis1_unit");
		disableItem("d_axis1_position");
		disableItem("d_axis1_format");
	}
	else
	{
		enableItem("d_axis1_name");
		enableItem("d_axis1_unit");
		enableItem("d_axis1_position");
		enableItem("d_axis1_format");
	}

	if ( (Serie1Binding != 2 || !Serie1Enabled) && (Serie2Binding != 2 || !Serie2Enabled) && (Serie3Binding != 2 || !Serie3Enabled) )
	{
		disableItem("d_axis2_name");
		disableItem("d_axis2_unit");
		disableItem("d_axis2_position");
		disableItem("d_axis2_format");
	}
	else
	{
		enableItem("d_axis2_name");
		enableItem("d_axis2_unit");
		enableItem("d_axis2_position");
		enableItem("d_axis2_format");
	}

	if ( Automatic )
	{
		sl_enabled  = document.getElementById("sl_enabled").checked;
		g_width     = document.getElementById("g_width").value;
		g_height    = document.getElementById("g_height").value;
		s_direction = document.getElementById("s_direction").options[document.getElementById("s_direction").selectedIndex].value;

		leftSeries = 0;
		rightSeries = 0;

		if ( !document.getElementById("d_axis0_position").disabled && getSelected("d_axis0_position") == "left" ) { leftSeries++; }
		if ( !document.getElementById("d_axis0_position").disabled && getSelected("d_axis0_position") == "right" ) { rightSeries++; }
		if ( !document.getElementById("d_axis1_position").disabled && getSelected("d_axis1_position") == "left" ) { leftSeries++; }
		if ( !document.getElementById("d_axis1_position").disabled && getSelected("d_axis1_position") == "right" ) { rightSeries++; }
		if ( !document.getElementById("d_axis2_position").disabled && getSelected("d_axis2_position") == "right" ) { rightSeries++; }
		if ( !document.getElementById("d_axis2_position").disabled && getSelected("d_axis2_position") == "right" ) { rightSeries++; }

		if ( s_direction == "SCALE_POS_LEFTRIGHT" )
		{
			leftOffset = (leftSeries == 0 ? 20 : 10);
			rightOffset = (rightSeries == 0 ? 25 : 15);

			leftMargin = leftOffset + 40 * leftSeries;
			width = g_width - leftMargin - 40 * rightSeries - rightOffset;

			BottomOffset = (sl_enabled ? Series*15 : 0);

			document.getElementById("s_x").value = leftMargin;
			document.getElementById("s_y").value = 50;
			document.getElementById("s_width").value = width;
			document.getElementById("s_height").value = g_height - 50 - 40 - BottomOffset;
		}
		else
		{
			topOffset = (leftSeries == 0 ? 40 : 40);
			bottomOffset = (rightSeries == 0 ? 25 : 15);

			topMargin = topOffset + 30 * leftSeries;
			height = g_height - topMargin - 30 * rightSeries - bottomOffset;

			RightOffset = (sl_enabled ? Series*15 : 0);

			document.getElementById("s_x").value = 70;
			document.getElementById("s_y").value = topMargin;
			document.getElementById("s_width").value = g_width - 70 - 40 - RightOffset;
			document.getElementById("s_height").value = height;
		}
	}
}

function disableItem(ID)
{
	document.getElementById(ID).style.backgroundColor = "#E0E0E0";
	document.getElementById(ID).style.color = "#A0A0A0";
	document.getElementById(ID).disabled = true;
}

function disableCheck(ID)
{
	document.getElementById(ID).style.color = "#A0A0A0";
	document.getElementById(ID).disabled = true;
}

function disableRadio(ID)
{
	document.getElementById(ID).disabled = true;
}

function enableItem(ID)
{
	document.getElementById(ID).style.backgroundColor = "#FFFFFF";
	document.getElementById(ID).style.color = "#707070";
	document.getElementById(ID).disabled = false;
}

function enableCheck(ID)
{
	document.getElementById(ID).style.color = "#707070";
	document.getElementById(ID).disabled = false;
}

function enableRadio(ID)
{
	document.getElementById(ID).disabled = false;
}

function setDefaultAbsissa()
{
	document.getElementById("d_absissa_data0").value = "January";
	document.getElementById("d_absissa_data1").value = "February";
	document.getElementById("d_absissa_data2").value = "March";
	document.getElementById("d_absissa_data3").value = "April";
	document.getElementById("d_absissa_data4").value = "May";
	document.getElementById("d_absissa_data5").value = "June";
	document.getElementById("d_absissa_data6").value = "July";
	document.getElementById("d_absissa_data7").value = "August";
}

function push(URL,nextStep)
{
	$.ajax({
		type: "GET",
		url: URL,
		beforeSend: function(){
			$("#result_area").html("<img src='graphix/wait.gif'><br />Working");
		},
		success: function (result) {
			switch (nextStep) {
				case 1:
					saveData();
					break;
				case 2:
					saveScale();
					break;
				case 3:
					saveChart();
					break;
				case 4:
					saveLegend();
					break;
				case 5:
					if ( Action == "Render" ) {
						$("#result_area").html("<center><img src='script/render.php' /></center>");
					} else {
						push("script/render.php?Mode=Source",6);
					}
					break;
				case 6:
					$("#result_area").html("<pre name='code'>"+result+"</pre>");
			}
		}
	});
}

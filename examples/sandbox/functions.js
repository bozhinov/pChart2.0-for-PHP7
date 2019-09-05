/*
functions.js - Sandbox JS

Version     : 1.2.2
Made by     : Jean-Damien POGOLOTTI
Maintainedby: Momchil Bozhinov
Last Update : 03/09/19
This file can be distributed under the license you can find at :

			http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

function Do(Action)
{
	post(prepPOST(), Action);
}

function prepPOST()
{
	$("#result_area").html("<img src='graphix/wait.gif' /><br />Saving configuration");

	objs = [input4POST(), selected4POST()],
	POST =  objs.reduce(function (r, o) {
        Object.keys(o).forEach(function (k) {
            r[k] = o[k];
        });
        return r;
    }, {});

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

	POST["data0"] = data0;
	POST["data1"] = data1;
	POST["data2"] = data2;
	POST["absissa"] = absissa;

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

	POST["t_axis"] = t_axis;

	return JSON.stringify(POST);
}

function input4POST()
{
	var List = {};

	let inputs = document.querySelectorAll('input');

	for (var i = 0; i < inputs.length; i++) {
		if ((inputs[i].type == "checkbox") || (inputs[i].type == "radio")){
			List[inputs[i].id] = inputs[i].checked;
		} else if (inputs[i].type == "text"){
			List[inputs[i].id] = inputs[i].value;
		}
	};

	return List;
}

function selected4POST()
{
	var List = {};

	let selects = document.querySelectorAll('select');

	for (var i = 0; i < selects.length; i++) {
		var e = document.getElementById(selects[i].id);
		List[selects[i].id] = e.options[e.selectedIndex].value;
	};

	return List;
}

function post(json_data, Action)
{
	$.ajax({
		type: "POST",
		data: {Data : json_data, Action : Action},
		url: "render.php",
		success: function (result) {
			if (Action == "Code"){
				$("#result_area").html("<pre name='code'>"+result+"</pre>");
			} else if (Action == "Render") {
				$("#result_area").html("<img src=\"data:image/png;base64, "+result+"\" />");
			}
		},
		error: function() {
             $("#result_area").html("Post failed!");
        }
	});
}

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

function getSelected(ID)
{
	e = document.getElementById(ID);
	return e.options[e.selectedIndex].value;
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
	
	for( i = 0; i < 3; i++ )
	{
		if ( (Serie1Binding != i || !Serie1Enabled) && (Serie2Binding != i || !Serie2Enabled) && (Serie3Binding != i || !Serie3Enabled) )
		{
			disableItem("d_axis" + i + "_name");
			disableItem("d_axis" + i + "_unit");
			disableItem("d_axis" + i + "_position");
			disableItem("d_axis" + i + "_format");
		}
		else
		{
			enableItem("d_axis" + i + "_name");
			enableItem("d_axis" + i + "_unit");
			enableItem("d_axis" + i + "_position");
			enableItem("d_axis" + i + "_format");
		}
	}

	if ( Automatic )
	{
		sl_enabled  = document.getElementById("sl_enabled").checked;
		g_width     = document.getElementById("g_width").value;
		g_height    = document.getElementById("g_height").value;

		leftSeries = 0;
		rightSeries = 0;

		for ( i = 0; i < 3; i++ )
		{
			if (!document.getElementById("d_axis" + i + "_position").disabled){
				if (getSelected("d_axis" + i + "_position") == "left"){
					leftSeries++;
				} else {
					rightSeries++;
				}
			}
		}

		if ( getSelected("s_direction") == "SCALE_POS_LEFTRIGHT" )
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

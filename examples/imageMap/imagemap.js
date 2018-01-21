 /*
	imageMap - JS to handle image maps over pChart graphix

	Version     : 2.2.3
	Made by     : Jean-Damien POGOLOTTI
	MaintainedBy: Momchil Bozhinov
	Last Update : 10/01/18

	This file can be distributed under the license you can find at :

		http://www.pchart.net/license

	You can find the whole class documentation on the pChart web site.
 */
 
var cX	= 0;
var cY	= 0;
var LastcX	= 0;
var LastcY	= 0;
var currentTitle = "";
var currentMessage = "";
var SmoothMove = false;
var SmoothMoveFactor = 5;
var delimiter = String.fromCharCode(1);
var tooltipDiv = "#testDiv";	

 /* Show the tooltip */
function showDiv(Color,Title,Message)
{
	if (currentTitle != Title || currentMessage != Message) {
		var HTML = `
<div style="border:2px solid #606060">
	<div style="background-color: #000000; font-family: tahoma; font-size: 11px; color: #ffffff; padding: 4px;">
		<b>`+Title+` &nbsp;</b>
	</div>
	<div style="background-color: #808080; border-top: 2px solid #606060; font-family: tahoma; font-size: 10px; color: #ffffff; padding: 2px;">
		<table style="border: 0px; padding: 0px; margin: 0px;">
			<tr valign="top">
				<td style="padding-top: 4px;">
					<table style="background-color: `+Color+`; border: 1px solid #000000; width: 9px; height: 9px;  padding: 0px; margin: 0px; margin-right: 2px;">
						<tr>
							<td></td>
						</tr>
					</table>
				</td>
				<td>`+Message+`</td>
			</tr>
		</table>
	</div>
</div>`;

		$(tooltipDiv).html(HTML);
	} 

	currentTitle   = Title;
	currentMessage = Message;
}

 /* Move the div to the mouse location */
function moveDiv()
{
	if (SmoothMove)
	{ 
		cX = LastcX - (LastcX-cX)/4;
		cY = LastcY - (LastcY-cY)/SmoothMoveFactor;
	}
	
	var element = document.getElementById(tooltipDiv.substring(1));
	element.style.left = (cX+10) + "px";
	element.style.top  = (cY+10) + "px";

	LastcX = cX;
	LastcY = cY;
}

 /* Add a picture element that need ImageMap parsing */
function addImageMap(PictureID,ImageMapID,ImageMapURL)
{
		
	if ($("#".ImageMapID).length) {
		$("#".ImageMapID).remove();
	}
	
	var element = document.createElement("DIV");
	element.id             = tooltipDiv.substring(1);
	element.innerHTML      = "";
	element.style.display  = "inline-block";
	element.style.position = "absolute";
	document.body.appendChild(element);

	var element = document.createElement("MAP");
	element.id   = ImageMapID;
	element.name = ImageMapID;
	document.body.appendChild(element);
	document.getElementById(PictureID).useMap = "#"+ImageMapID;
	
	/* get the image map */
	$.get(ImageMapURL).done(function(data) {
		
		var i, Zones = data.split("\r\n");

		for(i=0;i<=Zones.length-2;i++)
		{
			addArea(Zones[i].split(delimiter), ImageMapID);
		}
	});
	
	 /* Attach the onMouseMove() event to picture frame */
	$("#"+ImageMapID).mousemove(function(e){
		cX = e.pageX; 
		cY = e.pageY;
		moveDiv(); 
	});
}

/* Add an area to the specified image map */
function addArea(Options, MapID)
{
	var maps    = document.getElementById(MapID);
	var element = document.createElement("AREA");

	element.shape  = Options[0];
	element.coords = Options[1];
	element.onmouseover = function() { showDiv(Options[2],Options[3],Options[4].replace('"','')); };
	element.onmouseout  = function() { $(tooltipDiv).html(""); };
	maps.appendChild(element);
}

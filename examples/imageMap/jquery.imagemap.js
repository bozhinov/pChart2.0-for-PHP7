/*
imageMap - jQuery plug-in to handle image maps over pChart graphics

Version     : 2.2.3
Made by     : Jean-Damien POGOLOTTI
MaintainedBy: Momchil Bozhinov
Last Update : 22/01/18

This file can be distributed under the license you can find at :

	http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/
 
(function( $ ) {

	var LastcX = 0;
	var LastcY = 0;
	var tooltipDivElement;
	
	/* Add a picture element that need ImageMap parsing */
	$.fn.addImageMap = function(ImageMapID, ImageMapURL, mySettings) 
	{
		var Settings = $.extend({
				SmoothMove: false,
				SmoothMoveFactor: 5,
				tooltipDiv: "ImageMapDiv" // mind the #
			}, mySettings );
				
		/* create ToolTipDiv if needed */
		if ($("#"+Settings.tooltipDiv).length) {
			document.getElementById(Settings.tooltipDiv).innerHTML = "";
		} else {
			var element = document.createElement("DIV");
			element.id             = Settings.tooltipDiv;
			element.innerHTML      = "";
			element.style.display  = "inline-block";
			element.style.position = "absolute";
			document.body.appendChild(element);
		}
		
		tooltipDivElement = document.getElementById(Settings.tooltipDiv);
		
		/* re create ImageMap element */
		if ($("#"+ImageMapID).length) {
			$("#"+ImageMapID).remove();
		}
		var element  = document.createElement("MAP");
		element.id   = ImageMapID;
		element.name = ImageMapID;
		document.body.appendChild(element);
		document.getElementById(this.attr('id')).useMap = "#"+ImageMapID;
				
		/* get the image map */
		var map = document.getElementById(ImageMapID);
		
		$.getJSON(ImageMapURL).done(function(data) {
			$.each(data, function(index, value) {
				/* Add an area to the specified image map */
				if (value.length == 5)
				{
					var element = document.createElement("AREA");
					element.shape  = value[0];
					element.coords = value[1];
					element.onmouseover = function() { showDiv(value[2], value[3], value[4].replace('"','')); };
					element.onmouseout  = function() { tooltipDivElement.innerHTML = ""; };
					map.appendChild(element);
				}
			});
		});

		/* Attach the onMouseMove() event to picture frame */
		$("#"+ImageMapID).mousemove(function(e){
			cX = e.pageX; 
			cY = e.pageY;
			if (Settings.SmoothMove)
			{ 
				cX = LastcX - (LastcX-cX)/4;
				cY = LastcY - (LastcY-cY)/Settings.SmoothMoveFactor;
			}
			/* Move the div to the mouse location */
			tooltipDivElement.style.left = (cX+10) + "px";
			tooltipDivElement.style.top  = (cY+10) + "px";

			LastcX = cX;
			LastcY = cY;
		});
		
		return this;
	};
	
	/* Show the tooltip */
	function showDiv(Color, Title, Message) 
	{
		tooltipDivElement.innerHTML = '\
			<div class="imageMapShell">\
				<div class="imageMapTitle">'+Title+'</div>\
				<div class="imageMapMain">\
					<div class="imageMapColorBox" style="background-color: '+Color+';"></div>\
					<div class="imageMapMessage">'+Message+'</div>\
				</div>\
			</div>';
	}

}( jQuery ));
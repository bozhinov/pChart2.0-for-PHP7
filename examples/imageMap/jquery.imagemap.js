/*
imageMap - jQuery plug-in to handle image maps over pChart graphics

Version     : 2.2.3
Made by     : Jean-Damien POGOLOTTI
MaintainedBy: Momchil Bozhinov
Last Update : 21/01/18

This file can be distributed under the license you can find at :

	http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/
 
(function( $ ) {

	var LastcX	= 0;
	var LastcY	= 0;
	var Settings;
	
	/* Add a picture element that need ImageMap parsing */
	$.fn.addImageMap = function(ImageMapID,ImageMapURL, mySettings) 
	{
		Settings = $.extend({
            SmoothMove: false,
            SmoothMoveFactor: 5,
			delimiter: String.fromCharCode(1),
			tooltipDiv: "#ImageMapDiv"
        }, mySettings );
		
		if ($("#".ImageMapID).length) {
			$("#".ImageMapID).remove();
		}

		var element = document.createElement("DIV");
		element.id             = Settings.tooltipDiv.substring(1);
		element.innerHTML      = "";
		element.style.display  = "inline-block";
		element.style.position = "absolute";
		document.body.appendChild(element);

		var element  = document.createElement("MAP");
		element.id   = ImageMapID;
		element.name = ImageMapID;
		document.body.appendChild(element);
		document.getElementById(this.attr('id')).useMap = "#"+ImageMapID;

		/* get the image map */
		$.get(ImageMapURL).done(function(data) {
			
			var i, Zones = data.split("\r\n");

			for(i=0;i<=Zones.length-2;i++)
			{
				addArea(Zones[i].split(Settings.delimiter), ImageMapID);
			}
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
			var element = document.getElementById(Settings.tooltipDiv.substring(1));
			element.style.left = (cX+10) + "px";
			element.style.top  = (cY+10) + "px";

			LastcX = cX;
			LastcY = cY;
		});
		
		return this;
	};
	
	/* Show the tooltip */
	function showDiv(Options) 
	{
		var Color = Options[2];
		var Title = Options[3];
		var Message = Options[4].replace('"','');
		
		$(Settings.tooltipDiv).html('<div style="border:2px solid #606060">\
		 <div style="background-color: #000000; font-family: tahoma; font-size: 11px; color: #ffffff; padding: 4px;">\
		  	<b>'+Title+' &nbsp;</b>\
		    </div>\
		    <div style="background-color: #808080; border-top: 2px solid #606060; font-family: tahoma; font-size: 10px; color: #ffffff; padding: 2px;">\
		  	<table style="border: 0px; padding: 0px; margin: 0px;">\
		  		<tr valign="top">\
		 			<td style="padding-top: 4px;">\
		  				<table style="background-color: '+Color+'; border: 1px solid #000000; width: 9px; height: 9px;  padding: 0px; margin: 0px; margin-right: 2px;">\
		  					<tr>\
		  						<td></td>\
		  					</tr>\
		  				</table>\
		  			</td>\
		  			<td>'+Message+'</td>\
		 		</tr>\
		  	</table>\
		  </div>\
		  </div>');
	}

	/* Add an area to the specified image map */
	function addArea(Options, MapID)
	{
		var maps    = document.getElementById(MapID);
		var element = document.createElement("AREA");

		element.shape  = Options[0];
		element.coords = Options[1];
		element.onmouseover = function() { showDiv(Options); };
		element.onmouseout  = function() { $(Settings.tooltipDiv).html(""); };
		maps.appendChild(element);
	}

}( jQuery ));
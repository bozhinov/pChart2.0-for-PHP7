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
 
class ImageMapper {
		
	constructor() {
		
		this.cX	= 0;
		this.cY	= 0;
		this.LastcX	= null;
		this.LastcY	= null;
		this.rX	= 0;
		this.rY	= 0;
		this.initialized = false;
		this.currentTitle = "";
		this.currentMessage = "";
		this.SmoothMove = false;
		this.SmoothMoveFactor = 5;
		this.imageMapRandomSeed = true;
		this.delimiter = String.fromCharCode(1);
		this.tooltipDiv = "#testDiv";
		 		
	}

	 /* Show the tooltip */
	showDiv(Color,Title,Message)
	{
		if (this.currentTitle != Title || this.currentMessage != Message) {
			var HTML = "<div style='border:2px solid #606060'><div style='background-color: #000000; font-family: tahoma; font-size: 11px; color: #ffffff; padding: 4px;'><b>"+Title+" &nbsp;</b></div>";
			HTML    += "<div style='background-color: #808080; border-top: 2px solid #606060; font-family: tahoma; font-size: 10px; color: #ffffff; padding: 2px;'>";
			HTML    += "<table style='border: 0px; padding: 0px; margin: 0px;'><tr valign='top'><td style='padding-top: 4px;'><table style='background-color: "+Color+"; border: 1px solid #000000; width: 9px; height: 9px;  padding: 0px; margin: 0px; margin-right: 2px;'><tr><td></td></tr></table></td><td>"+Message+"</td></tr></table>";
			HTML    += "</div></div>";

			$(this.tooltipDiv).html(HTML);
		} 

		if (!this.initialized) { 
			this.moveDiv(); 
			this.initialized = true;
		}

		$(this.tooltipDiv).css({opacity: 1});

		this.currentTitle   = Title;
		this.currentMessage	= Message;
	}

	 /* Move the div to the mouse location */
	moveDiv()
	{
		if(self.pageYOffset)
		{
			this.rX = self.pageXOffset;
			this.rY = self.pageYOffset; 
		} else if(document.documentElement && document.documentElement.scrollTop) {
				this.rX = document.documentElement.scrollLeft; 
				this.rY = document.documentElement.scrollTop; 
		} else if(document.body) { 
			this.rX = document.body.scrollLeft;
			this.rY = document.body.scrollTop;
		}
		   
		if (this.SmoothMove && this.LastcX != null)
		{ 
			this.cX = this.LastcX - (this.LastcX-this.cX)/4;
			this.cY = this.LastcY - (this.LastcY-this.cY)/this.SmoothMoveFactor;
		}
		
		$(this.tooltipDiv)[0].style.left = (this.cX+10) + "px";
		$(this.tooltipDiv)[0].style.top  = (this.cY+10) + "px";

		this.LastcX = this.cX;
		this.LastcY = this.cY;
	}

	 /* Add a picture element that need ImageMap parsing */
	addImage(PictureID,ImageMapID,ImageMapURL)
	{
		
		var element = document.createElement("DIV");

		element.id             = this.tooltipDiv.substring(1);
		element.innerHTML      = "";
		element.style.display  = "inline-block";
		element.style.position = "absolute";
		element.style.opacity  = 0;
		element.style.filter   = "alpha(opacity=0)";

		document.body.appendChild(element);
		
		if ($("#".ImageMapID).length) {
			$("#".ImageMapID).remove();
		}

		var element = document.createElement("MAP");

		element.id   = ImageMapID;
		element.name = ImageMapID;
		document.body.appendChild(element);
	   
		document.getElementById(PictureID).useMap = "#"+ImageMapID;

		if (this.imageMapRandomSeed){
			var randomSeed = "Seed=" + Math.floor(Math.random()*1000);
			if (ImageMapURL.indexOf("?",0) != -1) { 
				ImageMapURL = ImageMapURL + "&" + randomSeed; 
			} else { 
				ImageMapURL = ImageMapURL + "?" + randomSeed; 
			}
		}
	
		var that = this;
		$.get(ImageMapURL).done(function(data) {
			 that.parseZones(ImageMapID, data);
		});
		
		 /* Attach the onMouseMove() event to picture frame */
		$("#"+ImageMapID).mousemove(function(e){
			that.cX = e.pageX; 
			that.cY = e.pageY;
			that.moveDiv(); 
		});
	}

	 /* Process the image map & create the zones */
	parseZones(ImageMapID,SerializedZones)
	{
		var Zones = SerializedZones.split("\r\n");
		var i;
		for(i=0;i<=Zones.length-2;i++)
		{
			var Options = Zones[i].split(this.delimiter);
			this.addArea(ImageMapID,Options[0],Options[1],'that.showDiv("'+Options[2]+'","'+Options[3]+'","'+Options[4].replace('"','')+'");');
		}
	}
	

	/* Add an area to the specified image map */
	addArea(ImageMapID,shapeType,coordsList,actionOver)
	{
		var maps    = document.getElementById(ImageMapID);
		var element = document.createElement("AREA");
		var that = this;
		element.shape  = shapeType;
		element.coords = coordsList;
		element.onmouseover = function() { eval(actionOver); };
		element.onmouseout  = function() { $(that.tooltipDiv).css({opacity: 0}); };
		maps.appendChild(element);
	}

}

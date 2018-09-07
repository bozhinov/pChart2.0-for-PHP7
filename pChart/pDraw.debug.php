<?php
/*
pDraw.debug - class extension with drawing methods

All GD functions have been spoofed and stuffed into a buffer.
The the image is then created from the instructions in the buffer.
The buffer can also be dumped to a text file.
Visual comparison was fine up till a certain point, but at this stage of
development I need a quicker, more secure method to make sure the changes I make
do not result in a different image, so I compare the GD "instructions"

The debug routines result it 15 - 30% performance degradation 
I advise against using this code in production.

Version     : 2.3.0-dev
Made by     : Momchil Bozhinov
Last Update : 01/02/2018

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart;

/* The GD extension is mandatory */
if (!function_exists("gd_info")) {
	echo "GD extension must be loaded. \r\n";
	exit();
}

use pChart\pData;
use pChart\pException;

define("DIRECTION_VERTICAL", 690001);
define("DIRECTION_HORIZONTAL", 690002);
define("SCALE_POS_LEFTRIGHT", 690101);
define("SCALE_POS_TOPBOTTOM", 690102);
define("SCALE_MODE_FLOATING", 690201);
define("SCALE_MODE_START0", 690202);
define("SCALE_MODE_ADDALL", 690203);
define("SCALE_MODE_ADDALL_START0", 690204);
define("SCALE_MODE_MANUAL", 690205);
define("SCALE_SKIP_NONE", 690301);
define("SCALE_SKIP_SAME", 690302);
define("SCALE_SKIP_NUMBERS", 690303);
define("TEXT_ALIGN_TOPLEFT", 690401);
define("TEXT_ALIGN_TOPMIDDLE", 690402);
define("TEXT_ALIGN_TOPRIGHT", 690403);
define("TEXT_ALIGN_MIDDLELEFT", 690404);
define("TEXT_ALIGN_MIDDLEMIDDLE", 690405);
define("TEXT_ALIGN_MIDDLERIGHT", 690406);
define("TEXT_ALIGN_BOTTOMLEFT", 690407);
define("TEXT_ALIGN_BOTTOMMIDDLE", 690408);
define("TEXT_ALIGN_BOTTOMRIGHT", 690409);
define("POSITION_TOP", 690501);
define("POSITION_BOTTOM", 690502);
define("LABEL_POS_LEFT", 690601);
define("LABEL_POS_CENTER", 690602);
define("LABEL_POS_RIGHT", 690603);
define("LABEL_POS_TOP", 690604);
define("LABEL_POS_BOTTOM", 690605);
define("LABEL_POS_INSIDE", 690606);
define("LABEL_POS_OUTSIDE", 690607);
define("ORIENTATION_HORIZONTAL", 690701);
define("ORIENTATION_VERTICAL", 690702);
define("ORIENTATION_AUTO", 690703);
define("LEGEND_NOBORDER", 690800);
define("LEGEND_BOX", 690801);
define("LEGEND_ROUND", 690802);
define("LEGEND_VERTICAL", 690901);
define("LEGEND_HORIZONTAL", 690902);
define("LEGEND_FAMILY_BOX", 691051);
define("LEGEND_FAMILY_CIRCLE", 691052);
define("LEGEND_FAMILY_LINE", 691053);
define("DISPLAY_AUTO", 691001);
define("DISPLAY_MANUAL", 691002);
define("LABELING_ALL", 691011);
define("LABELING_DIFFERENT", 691012);
define("BOUND_MIN", 691021);
define("BOUND_MAX", 691022);
define("BOUND_BOTH", 691023);
define("BOUND_LABEL_POS_TOP", 691031);
define("BOUND_LABEL_POS_BOTTOM", 691032);
define("BOUND_LABEL_POS_AUTO", 691033);
define("CAPTION_LEFT_TOP", 691041);
define("CAPTION_RIGHT_BOTTOM", 691042);
define("GRADIENT_SIMPLE", 691051);
define("GRADIENT_EFFECT_CAN", 691052);
define("LABEL_TITLE_NOBACKGROUND", 691061);
define("LABEL_TITLE_BACKGROUND", 691062);
define("LABEL_POINT_NONE", 691071);
define("LABEL_POINT_CIRCLE", 691072);
define("LABEL_POINT_BOX", 691073);
define("ZONE_NAME_ANGLE_AUTO", 691081);
define("PI", 3.14159265);
define("ALL", 69);
define("NONE", 31);
define("AUTO", 690000);
define("OUT_OF_SIGHT", -10000000000000);

class pDraw
{

	var $aColorCache = [];
	/* Last generated chart info */
	var $isChartLayoutStacked = FALSE; // Last layout : regular or stacked
	/* Image settings, size, quality, .. */
	var $XSize = 0; // Width of the picture
	var $YSize = 0; // Height of the picture
	#var $Picture; // GD picture object
	var $Antialias = TRUE; // Turn anti alias on or off
	var $AntialiasQuality = 0; // Quality of the anti aliasing implementation (0-1)
	var $Mask = [];	// Already drawn pixels mask (Filled circle implementation) 
	var $TransparentBackground = FALSE; // Just to know if we need to flush the alpha channels when rendering
	/* Graph area settings */
	var $GraphAreaX1 = 0; // Graph area X origin
	var $GraphAreaY1 = 0; // Graph area Y origin
	var $GraphAreaX2 = 0; // Graph area bottom right X position
	var $GraphAreaY2 = 0; // Graph area bottom right Y position
	var $GraphAreaXdiff = 0; // $X2 - $X1
	var $GraphAreaYdiff = 0; // $Y2 - $Y1
	/* Scale settings */
	# var $ScaleMinDivHeight = 20; // Minimum height for scale divs # UNUSED
	/* Font properties */
	var $FontName = "pChart/fonts/GeosansLight.ttf"; // Default font file
	var $FontSize = 12; // Default font size
	#var $FontBox = NULL; // Return the bounding box of the last written string
	var $FontColor; // Default color settings
	/* Shadow properties */
	var $Shadow = FALSE; // Turn shadows on or off
	var $ShadowX = 0; // X Offset of the shadow
	var $ShadowY = 0; // Y Offset of the shadow
	var $ShadowColor; // R component of the shadow

	/* Data Set */
	var $myData = []; // Attached myData

	/* MOMCHIL: DEBUG CODE */
	var $Buff = [];
	var $Compression = 6;
	var $Filters = 0;

	/* Class constructor */
	function __construct(int $XSize, int $YSize, bool $TransparentBackground = FALSE)
	{
		$this->myData = new pData();
		$this->XSize = $XSize;
		$this->YSize = $YSize;

		if (!($XSize > 0 && $YSize > 0)){
			throw pException::InvalidDimentions("Image dimensions (X * Y) must be > 0!");
		}

		$memory_limit = ini_get("memory_limit");
		if (intval($memory_limit) * 1024 * 1024 < $XSize * $YSize * 3 * 1.7){ # Momchil: for black & white gifs -> use 1 and not 3
			echo "Memory limit: ".$memory_limit." Mb ".PHP_EOL;
			echo "Estimated required: ".round(($XSize * $YSize * 3 * 1.7)/(1024 * 1024), 3)." Mb ".PHP_EOL;
			throw pException::InvalidDimentions("Can not allocate enough memory for an image that big! Check your PHP memory_limit configuration option.");
		}
	}

	/*	Automatic output method based on the calling interface
	Momchil: Added support for Compression & Filters
	Compression must be between 0 and 9 -> http://php.net/manual/en/function.imagepng.php 
	http://php.net/manual/en/image.constants.php
	https://www.w3.org/TR/PNG-Filters.html
	*/
	function autoOutput(string $FileName = "output.png", int $Compression = 6, $Filters = PNG_NO_FILTER)
	{
		#$this->RenderFromDump("dump.txt");
		#$this->DumpBuffer("dump.txt");
		$this->Render((php_sapi_name() == "cli") ? $FileName : TRUE, $Compression, $Filters);
	}

	/* Destroy the image and start over. Required for pBarcodes */
	function resize(int $XSize, int $YSize)
	{
		$this->__construct($XSize, $YSize, $this->TransparentBackground);
	}

	function DumpBuffer($FileName)
	{
		$dump = "";

		foreach($this->Buff as $p){
			$dump .= json_encode($p).PHP_EOL;
		}

		$dump .= json_encode(['Meta' => [$this->XSize, $this->YSize, $this->TransparentBackground, $this->Filters, $this->Compression]]);

		file_put_contents($FileName, $dump);

	}

	function RenderFromDump($FileName)
	{

		$dump = file_get_contents($FileName);
		$Buff = explode(PHP_EOL, $dump);

		$Meta = array_pop($Buff);
		$Meta = json_decode($Meta, TRUE);
		$Compression = $Meta["Meta"][4];
		$Filters = $Meta["Meta"][3];
		$Picture = imagecreatetruecolor($Meta["Meta"][0], $Meta["Meta"][1]);
		$cC = [];

		if ($Meta["Meta"][2]) {
			imagealphablending($Picture, TRUE);
			imagesavealpha($Picture, TRUE);
		}

		foreach($Buff as $p){

			$p = json_decode($p, TRUE);

			$cId = $p[5][0].".".$p[5][1].".".$p[5][2].".".$p[5][3];
			if (!isset($cC[$cId])){
				$cC[$cId] = imagecolorallocatealpha($Picture, $p[5][0], $p[5][1], $p[5][2], $p[5][3]);
			}

			switch($p[0]){
				case 1:
					imagesetpixel($Picture, $p[1],$p[2],$cC[$cId]);
					break;
				case 2:
					imageline($Picture, $p[1],$p[2],$p[3],$p[4],$cC[$cId]);
					break;
				case 4:
					imagefilledrectangle($Picture, $p[1],$p[2],$p[3],$p[4],$cC[$cId]);
					break;
				case 5:
					imagerectangle($Picture, $p[1],$p[2],$p[3],$p[4],$cC[$cId]);
					break;
				case 6:
					imagettftext($Picture, $p[1],$p[2],$p[3],$p[4],$cC[$cId],$p[6],$p[7]);
					break;
				case 3:
					ImageFilledPolygon($Picture, $p[1],$p[2],$cC[$cId]);
					break;
			}

		}

		imagepng($Picture, $FileName.".png", $Compression, $Filters);
		imagedestroy($Picture);
	}

	function Render($FileName, int $Compression = 6, $Filters = PNG_NO_FILTER)
	{

		$Picture = imagecreatetruecolor($this->XSize, $this->YSize);
		$cC = [];

		if ($this->TransparentBackground) {
			imagealphablending($Picture, TRUE);
			imagesavealpha($Picture, TRUE);
		}

		foreach($this->Buff as $p){

			$cId = $p[5][0].".".$p[5][1].".".$p[5][2].".".$p[5][3];
			if (!isset($cC[$cId])){
				$cC[$cId] = imagecolorallocatealpha($Picture, $p[5][0], $p[5][1], $p[5][2], $p[5][3]);
			}

			switch($p[0]){
				case 1: 
					imagesetpixel($Picture, $p[1],$p[2],$cC[$cId]); # 2% pixel overlap
					break;
				case 2:
					imageline($Picture, $p[1],$p[2],$p[3],$p[4],$cC[$cId]);
					break;
				case 4:
					imagefilledrectangle($Picture, $p[1],$p[2],$p[3],$p[4],$cC[$cId]);
					break;
				case 5:
					imagerectangle($Picture, $p[1],$p[2],$p[3],$p[4],$cC[$cId]);
					break;
				case 6:
					imagettftext($Picture, $p[1],$p[2],$p[3],$p[4],$cC[$cId],$p[6],$p[7]);
					break;
				case 3:
					ImageFilledPolygon($Picture, $p[1],$p[2],$cC[$cId]);
					break;
			}

		}

		if($FileName == NULL){
			# Cache control should be done at a higher level
			header('Content-type: image/png');
		}
		imagepng($Picture, $FileName, $Compression, $Filters);
		imagedestroy($Picture);
	}

	/* http://php.net/manual/en/function.imagefilter.php */
	function setFilter(int $filtertype, int $arg1 = 0, int $arg2 = 0, int $arg3 = 0, int $arg4 = 0)
	{
		#TODO
	}

	function pImagesetpixel($X, $Y, $Color)
	{
		$this->Buff[] = [1, $X, $Y, 0, 0,$Color];
	}

	function pImageline($X, $Y, $XMin, $YMin, pColor $OuterBorderColor)
	{
		$this->Buff[] = [2, $X, $Y, $XMin, $YMin, $OuterBorderColor];
	}

	function pImageFilledPolygon($Points, $Num, pColor $Color)
	{
		$this->Buff[] = [3, $Points, $Num, 0, 0, $Color];
	}

	function pImagefilledrectangle($X1, $Y1, $X2, $Y2, pColor $Color)
	{
		$this->Buff[] = [4, $X1, $Y1, $X2, $Y2, $Color];
	}

	function pImagerectangle($X1, $Y1, $X2, $Y2, pColor $Color)
	{
		$this->Buff[] = [5, $X1, $Y1, $X2, $Y2, $Color];
	}

	function pImagettftext($FontSize, $Angle, $X, $Y, pColor $Color, $FontName, $Text)
	{
		$this->Buff[] = [6, $FontSize, $Angle, $X, $Y, $Color, $FontName, $Text];
	}

	/* Allocate a color with transparency */
	function allocateColor(pColor $Color) # FAST
	{
		$Id = $Color->getId();
		if (!isset($this->aColorCache[$Id])){

			$this->aColorCache[$Id] = [$Color->R, $Color->G, $Color->B, (1.27 * (100 - $Color->Alpha))];
		}

		return $this->aColorCache[$Id];
	}

}
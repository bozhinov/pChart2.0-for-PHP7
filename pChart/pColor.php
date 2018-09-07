<?php 
/*
pColor - Data structure for colors

Version     : 0.0.1
Made by     : Momchil Bozhinov
Last Update : 01/01/2018

*/

namespace pChart;

class pColor {

	var $R;
	var $G;
	var $B;
	var $Alpha;

	function __construct(int $R, int $G, int $B, float $Alpha = 100)
	{
		($R < 0)	AND $R = 0;
		($R > 255)	AND $R = 255;
		($G < 0) 	AND $G = 0;
		($G > 255) 	AND $G = 255;
		($B < 0) 	AND $B = 0;
		($B > 255) 	AND $B = 255;
		($Alpha < 0) AND $Alpha = 0;
		($Alpha > 100) AND $Alpha = 100;

		$this->R = $R;
		$this->G = $G;
		$this->B = $B;
		$this->Alpha = $Alpha;
	}

	public static function __set_state($saved){
		return new pColor($saved["R"],$saved["G"],$saved["B"],$saved["Alpha"]);
	}

	function getId(){
		return strval($this->R).".".strval($this->G).".".strval($this->B).".".strval($this->Alpha);
	}

	function toHTMLColor()
	{
		$R = dechex($this->R);
		$G = dechex($this->G);
		$B = dechex($this->B);

		return  "#".(strlen($R) < 2 ? '0' : '').$R.(strlen($G) < 2 ? '0' : '').$G.(strlen($B) < 2 ? '0' : '').$B;
	}

	function RGBChange(int $howmuch)
	{
		$this->R += $howmuch;
		$this->G += $howmuch;
		$this->B += $howmuch;

		($this->R < 0) AND $this->R = 0;
		($this->G < 0) AND $this->G = 0;
		($this->B < 0) AND $this->B = 0;
		($this->R > 255) AND $this->R = 255;
		($this->G > 255) AND $this->G = 255;
		($this->B > 255) AND $this->B = 255;	

		return $this;
	}

	function AlphaSet(float $howmuch)
	{
		$this->Alpha = $howmuch;

		($this->Alpha < 0)   AND $this->Alpha = 0;
		($this->Alpha > 100) AND $this->Alpha = 100;

		return $this;
	}

	function AlphaChange(float $howmuch)
	{
		$this->Alpha += $howmuch;

		($this->Alpha < 0)   AND $this->Alpha = 0;
		($this->Alpha > 100) AND $this->Alpha = 100;

		return $this;
	}

	function AlphaSlash(float $howmuch)
	{
		$this->Alpha = $this->Alpha / $howmuch;

		return $this;
	}

	function AlphaMultiply(float $howmuch)
	{
		$this->Alpha = $this->Alpha * $howmuch;

		($this->Alpha < 0)   AND $this->Alpha = 0;
		($this->Alpha > 100) AND $this->Alpha = 100;

		return $this;
	}

	function newOne()
	{
		return (clone $this);
	}

}

?>
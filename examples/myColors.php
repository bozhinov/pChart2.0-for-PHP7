<?php 

use pChart\pColor;

class myColors {

	public static function White(float $Alpha = 100)
	{
		return new pChart\pColor(255,255,255,$Alpha);
	}

	public static function Black(float $Alpha = 100)
	{
		return new pChart\pColor(0,0,0,$Alpha);
	}

	public static function Gray(float $Alpha = 100)
	{
		return new pChart\pColor(80,80,80,$Alpha);
	}

	public static function LightGray(float $Alpha = 100)
	{
		return new pChart\pColor(200,200,200,$Alpha);
	}

	public static function DarkGreen(float $Alpha = 10)
	{
		return new pChart\pColor(106,125,3,$Alpha);
	}
	
	public static function LightGreen(float $Alpha = 10)
	{
		return new pChart\pColor(181,209,27,$Alpha);
	}

	public static function LighterGreen(float $Alpha = 10)
	{
		return new pChart\pColor(191,219,37,$Alpha);
	}
	
	public static function myGridColor(float $Alpha = 100)
	{
		return ["StartColor"=>new pChart\pColor(240,240,240,$Alpha), "EndColor"=>new pChart\pColor(180,180,180,$Alpha)];
	}

	public static function myGreenGradient(float $Alpha = 100)
	{
		return ["StartColor"=>new pChart\pColor(217,250,116,$Alpha), "EndColor"=>new pChart\pColor(181,209,27,$Alpha)];
	}

}

?>
<?php 
/*
pColorGradient - Data structure for gradient color

Version     : 2.4.0-dev
Made by     : Momchil Bozhinov
Last Update : 01/09/2019

*/

namespace pChart;

class pColorGradient
{
	private $StartColor;
	private $EndColor;
	private $ReturnColor;
	private $OffsetR;
	private $OffsetG;
	private $OffsetB;
	private $OffsetAlpha;
	private $Step;

	function __construct(pColor $Start, pColor $End, $Radar = FALSE)
	{
		$this->StartColor = $Start;
		$this->EndColor = $End;
		$this->ReturnColor = ($Radar) ? $Start->newOne() : $Start;
	}

	public function SetSegments(int $Segments = 0)
	{
		if ($Segments == 0){
			$Segments = $this->Step;
		}
		$this->OffsetR = ($this->EndColor->R - $this->StartColor->R) / $Segments;
		$this->OffsetG = ($this->EndColor->G - $this->StartColor->G) / $Segments;
		$this->OffsetB = ($this->EndColor->B - $this->StartColor->B) / $Segments;
		$this->OffsetAlpha = ($this->EndColor->Alpha - $this->StartColor->Alpha) / $Segments;
	}

	/* pDraw uses default for $j */
	/* pRadar passes an actual value */
	public function Next(int $j = 1, bool $doNotAccumulate = FALSE)
	{
		$R = $this->StartColor->R + $this->OffsetR * $j;
		$G = $this->StartColor->G + $this->OffsetG * $j;
		$B = $this->StartColor->B + $this->OffsetB * $j;
		$Alpha = $this->StartColor->Alpha + $this->OffsetAlpha * $j;

		($R < 0)	AND $R = 0;
		($R > 255)	AND $R = 255;
		($G < 0) 	AND $G = 0;
		($G > 255) 	AND $G = 255;
		($B < 0) 	AND $B = 0;
		($B > 255) 	AND $B = 255;
		($Alpha < 0) AND $Alpha = 0;
		($Alpha > 100) AND $Alpha = 100;

		if ($doNotAccumulate){
			return new pColor($R,$G,$B,$Alpha);
		} else {
			$this->ReturnColor->R = $R;
			$this->ReturnColor->G = $G;
			$this->ReturnColor->B = $B;
			$this->ReturnColor->Alpha = $Alpha;
		}
	}

	public function getStart()
	{
		return $this->StartColor;
	}

	public function getEnd()
	{
		return $this->EndColor;
	}

	public function setStart(pColor $Start)
	{
		$this->StartColor = $Start;
	}

	public function setEnd(pColor $End)
	{
		$this->EndColor = $End;
	}

	public function isGradient()
	{
		return ($this->StartColor != $this->EndColor);
	}

	public function getLatest()
	{
		return $this->ReturnColor;
	}

	public function FindStep()
	{
		$this->Step = max(abs($this->EndColor->R - $this->StartColor->R), abs($this->EndColor->G - $this->StartColor->G), abs($this->EndColor->B - $this->StartColor->B));
		($this->Step == 0) AND $this->Step = 1;

		return $this->Step;
	}

}

?>
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

	private function getOffsets()
	{
		list($eR, $eG, $eB, $eA) = $this->EndColor->get();
		list($sR, $sG, $sB, $sA) = $this->StartColor->get();

		return [($eR - $sR), ($eG - $sG), ($eB - $sB), ($eA - $sA)];
	}

	public function SetSegments(int $Segments = 0)
	{
		if ($Segments == 0){
			$Segments = $this->Step;
		}

		list($oR, $oG, $oB, $oA) = $this->getOffsets();

		$this->OffsetR = $oR / $Segments;
		$this->OffsetG = $oG / $Segments;
		$this->OffsetB = $oB / $Segments;
		$this->OffsetAlpha = $oA / $Segments;
	}

	/* pDraw uses default for $j */
	/* pRadar passes an actual value */
	public function Next(int $j = 1, bool $doNotAccumulate = FALSE)
	{
		$j = abs($j);

		list($R, $G, $B, $Alpha) = $this->StartColor->get();

		$R += $this->OffsetR * $j;
		$G += $this->OffsetG * $j;
		$B += $this->OffsetB * $j;
		$Alpha += $this->OffsetAlpha * $j;

		($R > 255)	AND $R = 255;
		($G > 255) 	AND $G = 255;
		($B > 255) 	AND $B = 255;
		($Alpha > 100) AND $Alpha = 100;

		if ($doNotAccumulate){
			return new pColor($R,$G,$B,$Alpha);
		} else {
			$this->ReturnColor->__construct($R,$G,$B,$Alpha);
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
		list($oR, $oG, $oB, ) = $this->getOffsets();

		$this->Step = max(abs($oR), abs($oG), abs($oB), 1);

		return $this->Step;
	}

}

?>
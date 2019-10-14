<?php 
/*
pColorGradient - Data structure for gradient color

Version     : 2.4.0-dev
Made by     : Momchil Bozhinov
Last Update : 14/10/2019

*/

namespace pChart;

class pColorGradient
{
	private $StartColor;
	private $EndColor;
	private $ReturnColor;
	private $Offsets = NULL;
	private $Segments;

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

		return ["R" => ($eR - $sR), "G" => ($eG - $sG), "B" => ($eB - $sB), "Alpha" => ($eA - $sA)];
	}

	public function SetSegments(int $Segments)
	{
		if (is_null($this->Offsets)){
			$this->Offsets = $this->getOffsets();
		}
		$this->Segments = $Segments;
	}

	/* pDraw uses default for $j */
	/* pRadar passes an actual value */
	public function Next(int $j = 1, bool $doNotAccumulate = FALSE)
	{
		if ($doNotAccumulate){
			return $this->StartColor->newOne()->Slide($this->Offsets, abs($j)/$this->Segments);
		} else {
			$this->ReturnColor = $this->StartColor->Slide($this->Offsets, abs($j)/$this->Segments);
		}
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
		$this->Offsets = $this->getOffsets();

		list($oR, $oG, $oB, ) = array_values($this->Offsets);

		return max(abs($oR), abs($oG), abs($oB), 1);
	}

}

?>
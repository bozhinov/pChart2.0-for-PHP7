<?php

namespace Examples;

/* pData class definition */
class pDataExt extends \pChart\pData {
	
	private function __construct()
	{
		parent::__construct();
	}

	/* Return the max value of a given serie */
	public function getMax(string $Serie)
	{
		return (isset($this->Data["Series"][$Serie])) ? $this->Data["Series"][$Serie]["Max"] : 0;
	}

	/* Return the min value of a given serie */
	public function getMin(string $Serie)
	{
		return (isset($this->Data["Series"][$Serie])) ? $this->Data["Series"][$Serie]["Min"] : 0;
	}

	/* Return the average value of the given serie */
	public function getSerieAverage(string $Serie)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$SerieData = array_diff($this->Data["Series"][$Serie]["Data"], [VOID]);
			return (array_sum($SerieData) / count($SerieData));
		} else {
			throw \pChart\pException::InvalidInput("Invalid serie name");
		}
	}

	/* Return the geometric mean of the given serie */
	public function getGeometricMean(string $Serie)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$SerieData = array_diff($this->Data["Series"][$Serie]["Data"], [VOID]);
			$Seriesum = 1;
			foreach($SerieData as $Value) {
				$Seriesum = $Seriesum * $Value;
			}

			return pow($Seriesum, 1 / count($SerieData));
		} else {
			throw \pChart\pException::InvalidInput("Invalid serie name");
		}
	}

	/* Return the harmonic mean of the given serie */
	public function getHarmonicMean(string $Serie)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$SerieData = array_diff($this->Data["Series"][$Serie]["Data"], [VOID]);
			$Seriesum = 0;
			foreach($SerieData as $Value) {
				$Seriesum = $Seriesum + 1 / $Value;
			}

			return (count($SerieData) / $Seriesum);
		} else {
			throw \pChart\pException::InvalidInput("Invalid serie name");
		}
	}

	/* Return the standard deviation of the given serie */
	public function getStandardDeviation(string $Serie)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$Average = $this->getSerieAverage($Serie);
			$SerieData = array_diff($this->Data["Series"][$Serie]["Data"], [VOID]);
			$DeviationSum = 0;
			foreach($SerieData as $Value) {
				$DeviationSum += pow($Value - $Average, 2);
			}

			return sqrt($DeviationSum / count($SerieData)); # $SerieData could be zero
		} else {
			throw \pChart\pException::InvalidInput("Invalid serie name");
		}
	}

	/* Return the Coefficient of variation of the given serie */
	public function getCoefficientOfVariation(string $Serie)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$Average = $this->getSerieAverage($Serie);
			$StandardDeviation = $this->getStandardDeviation($Serie);
			return ($StandardDeviation != 0) ? ($StandardDeviation / $Average) : 0;
		} else {
			throw \pChart\pException::InvalidInput("Invalid serie name");
		}
	}

	/* Return the median value of the given serie */
	public function getSerieMedian(string $Serie)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$SerieData = array_diff($this->Data["Series"][$Serie]["Data"], [VOID]);
			sort($SerieData);
			$SerieCenter = floor(count($SerieData) / 2);
			return (isset($SerieData[$SerieCenter])) ? $SerieData[$SerieCenter] : 0;
		} else {
			throw \pChart\pException::InvalidInput("Invalid serie name");
		}
	}

	/* Add random values to a given serie */
	public function addRandomValues(string $SerieName, array $Options = [])
	{
		$Values = isset($Options["Values"]) ? $Options["Values"] : 20;
		$Min = isset($Options["Min"]) ? $Options["Min"] : 0;
		$Max = isset($Options["Max"]) ? $Options["Max"] : 100;
		$withFloat = isset($Options["withFloat"]) ? $Options["withFloat"] : FALSE;

		$Points = [];
		for ($i = 0; $i <= $Values; $i++) {
			$Points[] = ($withFloat) ? (rand($Min * 100, $Max * 100) / 100) : rand($Min, $Max);
		}

		$this->addPoints($Points, $SerieName);
	}

	public function negateValues(array $Series)
	{
		foreach($Series as $SerieName) {
			if (isset($this->Data["Series"][$SerieName])) {
				$Data = [];
				foreach($this->Data["Series"][$SerieName]["Data"] as $Value) {
					$Data[] = ($Value == VOID) ? VOID : - $Value;
				}

				$this->Data["Series"][$SerieName]["Data"] = $Data;
				$Data = array_diff($Data, [VOID]);
				$this->Data["Series"][$SerieName]["Max"] = max($Data);
				$this->Data["Series"][$SerieName]["Min"] = min($Data);
			}
		}
	}

}


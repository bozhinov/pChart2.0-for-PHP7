<?php
/*
pDraw - class to manipulate data arrays

Version     : 2.4.0-dev
Made by     : Jean-Damien POGOLOTTI
Maintainedby: Momchil Bozhinov
Last Update : 01/09/2019

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart;

/* pData class definition */
class pData
{
	private $Data;
	private $Palette;

	function __construct()
	{
		$this->Palette = [
			new pColor(188,224,46,100),
			new pColor(224,100,46,100),
			new pColor(224,214,46,100),
			new pColor(46,151,224,100),
			new pColor(176,46,224,100),
			new pColor(224,46,117,100),
			new pColor(92,224,46,100),
			new pColor(224,176,46,100)
		];

		$this->Data = [
			"XAxis" => [
				"Display" => AXIS_FORMAT_DEFAULT,
				"Format" => NULL,
				"Name" => NULL,
				"Unit" => NULL
			],
			"Abscissa" => NULL,
			"AbsicssaPosition" => AXIS_POSITION_BOTTOM,
			"Axis" => [0 => [
					"Display" => AXIS_FORMAT_DEFAULT,
					"Position" => AXIS_POSITION_LEFT,
					"Identity" => AXIS_Y
				]
			]
		];
	}

	/* Initialize a given serie */
	public function initialise(string $Serie)
	{
		$ID = (isset($this->Data["Series"])) ? count($this->Data["Series"]) : 0;

		$this->Data["Series"][$Serie] = [
			"Data" => [],
			"Description" => $Serie,
			"isDrawable" => TRUE,
			"Picture" => NULL,
			"Max" => 0,
			"Min" => 0,
			"Axis" => 0,
			"Ticks" => NULL,
			"Weight" => NULL,
			"XOffset" => 0,
			"Shape" => SERIE_SHAPE_FILLEDCIRCLE,
			"Color" => (isset($this->Palette[$ID])) ? $this->Palette[$ID] : new pColor()
		];
	}

	/* Add a single point or an array to the given serie */
	public function addPoints(array $Values, string $SerieName = "Serie1")
	{
		if (!isset($this->Data["Series"][$SerieName])){
			$this->initialise($SerieName);
		}

		foreach($Values as $Value) {
			$this->Data["Series"][$SerieName]["Data"][] = $Value;
		}

		$StrippedData = array_diff($this->Data["Series"][$SerieName]["Data"], [VOID]);

		if (empty($StrippedData)) {
			$this->Data["Series"][$SerieName]["Max"] = 0;
			$this->Data["Series"][$SerieName]["Min"] = 0;
		} else {
			$this->Data["Series"][$SerieName]["Max"] = max($StrippedData);
			$this->Data["Series"][$SerieName]["Min"] = min($StrippedData);
		}
	}

	/* In case you add points to the a serie with the same name - pSplit */
	public function clearPoints(string $SerieName = "Serie1")
	{
		$this->Data["Series"][$SerieName]["Data"] = [];
		$this->Data["Series"][$SerieName]["Max"] = 0;
		$this->Data["Series"][$SerieName]["Min"] = 0;
	}

	/* Return the number of values contained in a given serie */
	public function getSerieCount(string $Serie) # UNUSED
	{
		return (isset($this->Data["Series"][$Serie]["Data"])) ? count($this->Data["Series"][$Serie]["Data"]) : 0;
	}

	/* Remove a serie from the pData object */
	public function removeSerie(string $Serie)  # UNUSED
	{
		if (isset($this->Data["Series"][$Serie])) {
			unset($this->Data["Series"][$Serie]);
		} else {
			throw pException::InvalidInput("Invalid serie name");
		}
	}

	public function resetSeriesColors() # pBubble
	{
		$ID = 0;
		foreach($this->Data["Series"] as $SerieName => $SeriesParameters) {
			if ($SeriesParameters["isDrawable"]) {
				$this->Data["Series"][$SerieName]["Color"] = $this->Palette[$ID];
				$ID++;
			}
		}
	}

	/* Reverse the values in the given serie */
	public function reverseSerie(string $Serie) # UNUSED
	{
		if (isset($this->Data["Series"][$Serie]["Data"])) {
			$this->Data["Series"][$Serie]["Data"] = array_reverse($this->Data["Series"][$Serie]["Data"]);
		} else {
			throw pException::InvalidInput("Invalid serie name");
		}
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

	/* Set the description of a given serie */
	public function setSerieShape(string $Serie, int $Shape = SERIE_SHAPE_FILLEDCIRCLE)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$this->Data["Series"][$Serie]["Shape"] = $Shape;
		} else {
			throw pException::InvalidInput("Invalid serie name");
		}
	}

	/* Set the description of a given serie */
	public function setSerieDescription(string $Serie, string $Description = "My serie")
	{
		if (isset($this->Data["Series"][$Serie])) {
			$this->Data["Series"][$Serie]["Description"] = $Description;
		} else {
			throw pException::InvalidInput("Invalid serie name");
		}
	}

	/* Set a serie as "drawable" while calling a rendering function */
	public function setSerieDrawable(string $Serie, bool $Drawable = TRUE)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$this->Data["Series"][$Serie]["isDrawable"] = $Drawable;
		} else {
			throw pException::InvalidInput("Invalid serie name");
		}
	}

	/* Set the icon associated to a given serie */
	public function setSeriePicture(string $Serie, string $Picture = "XX")
	{
		if (isset($this->Data["Series"][$Serie])) {
			if (!file_exists($Picture)){
				throw pException::InvalidInput("Serie picture could not be found");
			}
			$this->Data["Series"][$Serie]["Picture"] = $Picture;
		} else {
			throw pException::InvalidInput("Invalid serie name");
		}
	}

	/* Set the properties of the X Axis */
	public function setXAxisProperties($Props)
	{
		(isset($Props["Name"]))    AND $this->Data["XAxis"]["Name"]    = strval($Props["Name"]);
		(isset($Props["Display"])) AND $this->Data["XAxis"]["Display"] = intval($Props["Display"]);
		(isset($Props["Format"]))  AND $this->Data["XAxis"]["Format"]  = $Props["Format"];
		(isset($Props["Unit"]))    AND $this->Data["XAxis"]["Unit"]    = strval($Props["Unit"]);
	}

	/* Set the serie that will be used as abscissa */
	public function setAbscissa(string $Serie)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$this->Data["Abscissa"] = $Serie;
		} else {
			throw pException::InvalidInput("Invalid serie name");
		}
	}

	public function setAbsicssaPosition(int $Position = AXIS_POSITION_BOTTOM)
	{
		$this->Data["AbsicssaPosition"] = $Position;
	}

	/* Set the name of the abscissa axis */
	public function setAbscissaName(string $Name)
	{
		$this->Data["AbscissaName"] = $Name;
	}

	/* Return the abscissa margin */
	public function getAbscissaMargin()
	{
		foreach($this->Data["Axis"] as $Values) {
			if ($Values["Identity"] == AXIS_X) {
				return $Values["Margin"];
			}
		}

		throw pException::InvalidInput("No margin set!");
	}

	/* Create a scatter group specifying X and Y data series */
	public function setScatterSerie(string $SerieX, string $SerieY, int $ID = 0)
	{
		if (isset($this->Data["Series"][$SerieX]) && isset($this->Data["Series"][$SerieY])) {
			$this->initScatterSerie($ID);
			$this->Data["ScatterSeries"][$ID]["X"] = $SerieX;
			$this->Data["ScatterSeries"][$ID]["Y"] = $SerieY;
		} else {
			throw pException::InvalidInput("Invalid scatter serie names");
		}
	}

	/* Combination of 
		setScatterSerieShape
		setScatterSerieDescription
		setScatterSeriePicture
		setScatterSerieDrawable
		setScatterSerieTicks
		setScatterSerieWeight
		setScatterSerieColor
	*/
	public function setScatterSerieProperties(int $ID, array $Props)
	{
		if (isset($this->Data["ScatterSeries"][$ID])) {

			(isset($Props["Shape"]))	AND $this->Data["ScatterSeries"][$ID]["Shape"]	     = intval($Props["Shape"]);
			(isset($Props["Description"]))	AND $this->Data["ScatterSeries"][$ID]["Description"] = strval($Props["Description"]);
			(isset($Props["Picture"]))	AND $this->Data["ScatterSeries"][$ID]["Picture"]     = strval($Props["Picture"]);
			(isset($Props["Drawable"]))	AND $this->Data["ScatterSeries"][$ID]["Drawable"]    = boolval($Props["Drawable"]);
			(isset($Props["Ticks"]))	AND $this->Data["ScatterSeries"][$ID]["Ticks"]	     = intval($Props["Ticks"]);
			if (isset($Props["Color"])) {
				if ($Props["Color"] instanceof pColor){
					$this->Data["ScatterSeries"][$ID]["Color"] = $Props["Color"];
				} else {
					throw pException::InvalidInput("Invalid Color format");
				}
			}
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Set the shape of a given scatter serie */
	public function setScatterSerieShape(int $ID, int $Shape = SERIE_SHAPE_FILLEDCIRCLE)
	{
		if (isset($this->Data["ScatterSeries"][$ID])) {
			$this->Data["ScatterSeries"][$ID]["Shape"] = $Shape;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Set the description of a given scatter serie */
	public function setScatterSerieDescription(int $ID, string $Description = "My serie")
	{
		if (isset($this->Data["ScatterSeries"][$ID])) {
			$this->Data["ScatterSeries"][$ID]["Description"] = $Description;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Set the icon associated to a given scatter serie */
	public function setScatterSeriePicture(int $ID, string $Picture = "xx")
	{
		if (isset($this->Data["ScatterSeries"][$ID])) {
			if (!file_exists($Picture)){
				throw pException::InvalidInput("ScatterSerie picture could not be found");
			}
			$this->Data["ScatterSeries"][$ID]["Picture"] = $Picture;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Set a scatter serie as "drawable" while calling a rendering function */
	public function setScatterSerieDrawable(int $ID, bool $Drawable = TRUE)
	{
		if (isset($this->Data["ScatterSeries"][$ID])) {
			$this->Data["ScatterSeries"][$ID]["isDrawable"] = $Drawable;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Define if a scatter serie should be draw with ticks */
	public function setScatterSerieTicks(int $ID, int $Ticks = NULL)
	{
		if (isset($this->Data["ScatterSeries"][$ID])) {
			$this->Data["ScatterSeries"][$ID]["Ticks"] = $Ticks;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Define if a scatter serie should be draw with a special weight */
	public function setScatterSerieWeight(int $ID, int $Weight = NULL) # UNUSED
	{
		if (isset($this->Data["ScatterSeries"][$ID])) {
			$this->Data["ScatterSeries"][$ID]["Weight"] = $Weight;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Associate a color to a scatter serie */
	public function setScatterSerieColor(int $ID, pColor $Color)
	{
		if (isset($this->Data["ScatterSeries"][$ID])) {
			$this->Data["ScatterSeries"][$ID]["Color"] = $Color;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Compute the series limits for an individual and global point of view */
	public function limits()
	{
		$GlobalMin = PHP_INT_MIN;
		$GlobalMax = PHP_INT_MAX;
		foreach($this->Data["Series"] as $Key => $Value) {
			if ($this->Data["Abscissa"] != $Key && $this->Data["Series"][$Key]["isDrawable"]) {
				if ($GlobalMin > $this->Data["Series"][$Key]["Min"]) {
					$GlobalMin = $this->Data["Series"][$Key]["Min"];
				}

				if ($GlobalMax < $this->Data["Series"][$Key]["Max"]) {
					$GlobalMax = $this->Data["Series"][$Key]["Max"];
				}
			}
		}

		$this->Data["Min"] = $GlobalMin;
		$this->Data["Max"] = $GlobalMax;
		return [$GlobalMin,$GlobalMax];
	}

	/* Mark all series as drawable */
	public function setAllDrawable()
	{
		foreach($this->Data["Series"] as $Key => $Value) {
			if ($this->Data["Abscissa"] != $Key) {
				$this->Data["Series"][$Key]["isDrawable"] = TRUE;
			}
		}
	}

	/* Return the average value of the given serie */
	public function getSerieAverage(string $Serie)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$SerieData = array_diff($this->Data["Series"][$Serie]["Data"], [VOID]);
			return (array_sum($SerieData) / count($SerieData));
		} else {
			throw pException::InvalidInput("Invalid serie name");
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
			throw pException::InvalidInput("Invalid serie name");
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
			throw pException::InvalidInput("Invalid serie name");
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
			throw pException::InvalidInput("Invalid serie name");
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
			throw pException::InvalidInput("Invalid serie name");
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
			throw pException::InvalidInput("Invalid serie name");
		}
	}

	/* Return the x th percentile of the given serie */
	public function getSeriePercentile(string $Serie = "Serie1", float $Percentil = 95) # UNUSED
	{
		if (!isset($this->Data["Series"][$Serie]["Data"])) {
			throw pException::InvalidInput("Invalid serie name");
		}

		$Values = count($this->Data["Series"][$Serie]["Data"]) - 1;
		($Values < 0) AND $Values = 0;
		$PercentilID = floor(($Values / 100) * $Percentil + .5);
		$SortedValues = $this->Data["Series"][$Serie]["Data"];
		sort($SortedValues);
		return (is_numeric($SortedValues[$PercentilID])) ? $SortedValues[$PercentilID] : 0;
	}

	/* Add random values to a given serie */
	public function addRandomValues(string $SerieName = "Serie1", array $Options = [])
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

	/* Test if we have valid data */
	public function containsData() # UNUSED
	{
		if (!isset($this->Data["Series"])) {
			return FALSE;
		}

		foreach($this->Data["Series"] as $Key => $Value) {
			if ($this->Data["Abscissa"] != $Key && $this->Data["Series"][$Key]["isDrawable"]) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/* Set the display mode of an Axis */
	public function setAxisDisplay(int $AxisID, int $Mode = AXIS_FORMAT_DEFAULT, $Format = "")
	{
		if (isset($this->Data["Axis"][$AxisID])) {
			$this->Data["Axis"][$AxisID]["Display"] = $Mode;
			if ($Format != "") {
				$this->Data["Axis"][$AxisID]["Format"] = $Format;
			}
		}
	}

	/* Set the position of an Axis */
	public function setAxisPosition(int $AxisID, int $Position = AXIS_POSITION_LEFT)
	{
		if (isset($this->Data["Axis"][$AxisID])) {
			$this->Data["Axis"][$AxisID]["Position"] = $Position;
		} else {
			throw pException::InvalidInput("Invalid Axis ID");
		}
	}

	/* Combination of setAxisDisplay, setAxisPosition, setAxisUnit, setAxisName, setAxisColor & setAxisXY */
	public function setAxisProperties(int $AxisID, array $Props)
	{
		if (isset($this->Data["Axis"][$AxisID])) {

			(isset($Props["Unit"]))     AND $this->Data["Axis"][$AxisID]["Unit"] 	 = strval($Props["Unit"]);
			(isset($Props["Name"]))     AND $this->Data["Axis"][$AxisID]["Name"] 	 = strval($Props["Name"]);
			(isset($Props["Display"]))  AND $this->Data["Axis"][$AxisID]["Display"]  = intval($Props["Display"]);
			(isset($Props["Format"]))   AND $this->Data["Axis"][$AxisID]["Format"] 	 = strval($Props["Format"]);
			(isset($Props["Position"])) AND $this->Data["Axis"][$AxisID]["Position"] = intval($Props["Position"]);
			(isset($Props["Identity"])) AND $this->Data["Axis"][$AxisID]["Identity"] = intval($Props["Identity"]);
			if (isset($Props["Color"])) {
				if ($Props["Color"] instanceof pColor){
					$this->Data["Axis"][$AxisID]["Color"] = $Props["Color"];
				} else {
					throw pException::InvalidInput("Invalid Color format");
				}
			}
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Associate an unit to an axis */
	public function setAxisUnit(int $AxisID, string $Unit)
	{
		if (isset($this->Data["Axis"][$AxisID])) {
			$this->Data["Axis"][$AxisID]["Unit"] = $Unit;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Associate a name to an axis */
	public function setAxisName(int $AxisID, string $Name)
	{
		if (isset($this->Data["Axis"][$AxisID])) {
			$this->Data["Axis"][$AxisID]["Name"] = $Name;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Associate a color to an axis */
	public function setAxisColor(int $AxisID, pColor $Color)
	{
		if (isset($this->Data["Axis"][$AxisID])) {
			$this->Data["Axis"][$AxisID]["Color"] = $Color;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Design an axis as X or Y member */
	public function setAxisXY(int $AxisID, int $Identity = AXIS_Y)
	{
		if (isset($this->Data["Axis"][$AxisID])) {
			$this->Data["Axis"][$AxisID]["Identity"] = $Identity;
		} else {
			throw pException::InvalidInput("Invalid serie ID");
		}
	}

	/* Associate one data serie with one axis */
	public function setSerieOnAxis(string $Serie, int $AxisID)
	{
		$PreviousAxis = $this->Data["Series"][$Serie]["Axis"];
		/* Create missing axis */
		if (!isset($this->Data["Axis"][$AxisID])) {
			$this->Data["Axis"][$AxisID]["Position"] = AXIS_POSITION_LEFT;
			$this->Data["Axis"][$AxisID]["Identity"] = AXIS_Y;
		}

		$this->Data["Series"][$Serie]["Axis"] = $AxisID;
		/* Cleanup unused axis */
		$Found = FALSE;
		foreach($this->Data["Series"] as $SerieName => $Values) {
			if ($Values["Axis"] == $PreviousAxis) {
				$Found = TRUE;
			}
		}

		if (!$Found) {
			unset($this->Data["Axis"][$PreviousAxis]);
		}
	}

	public function getAxisData(int $AxisID)
	{
		return $this->Data["Axis"][$AxisID];
	}

	/* Define if a serie should be draw with ticks */
	public function setSerieTicks(string $Serie, int $Ticks = NULL)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$this->Data["Series"][$Serie]["Ticks"] = $Ticks;
		} else {
			throw pException::InvalidInput("Invalid serie name");
		}
	}

	/* Define if a serie should be draw with a special weight */
	public function setSerieWeight(string $Serie, int $Weight = NULL)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$this->Data["Series"][$Serie]["Weight"] = $Weight;
		} else {
			throw pException::InvalidInput("Invalid serie name");
		}
	}

	/* Returns the palette of the given serie */
	public function getSeriePalette(string $Serie)
	{
		if (!isset($this->Data["Series"][$Serie])) {
			throw pException::InvalidInput("Invalid serie name");
		} else {
			return $this->Data["Series"][$Serie]["Color"];
		}
	}

	/* Set the color of one serie */
	/* Momchil: tried to refactor. did not work */
	public function setPalette(string $Serie, pColor $Color)
	{
		if (isset($this->Data["Series"][$Serie])) {
			$Old = $this->Data["Series"][$Serie]["Color"];
			$this->Data["Series"][$Serie]["Color"] = $Color;
			/* Do reverse processing on the internal palette array */
			foreach($this->Palette as $Key => $Value) {
				if ($Value == $Old) {
					$this->Palette[$Key] = $Color;
				}
			}
		} else {
			throw pException::InvalidInput("Invalid serie name");
		}
	}

	/* Load a palette file */
	public function loadPalette(array $MyPalette, bool $Reset = FALSE)
	{
		if ($Reset) {
			$this->Palette = [];
		}

		foreach($MyPalette as $ID => $color){
			if (is_array($color)) {
				$this->Palette[$ID] = new pColor($color[0], $color[1], $color[2], $color[3]);
			} else {
				throw pException::InvalidInput("Invalid palette");
			}
		}

		/* Apply changes to current series */
		if (isset($this->Data["Series"])) {
			/* Momchil: no unit test gets here */
			foreach(array_keys($this->Data["Series"]) as $ID => $Key) {
				$this->Data["Series"][$Key]["Color"] = (!isset($this->Palette[$ID])) ? new pColor(0,0,0,0) : $this->Palette[$ID];
			}
		}
	}

	/* used in pPie */
	public function getPieParams($forLegend = FALSE)
	{
		/* Do we have an abscissa serie defined? */
		if ($this->Data["Abscissa"] == "" || !in_array($this->Data["Abscissa"], array_keys($this->Data["Series"]))) {
			throw pException::PieNoAbscissaException();
		} else {
			$AbscissaData = $this->Data["Series"][$this->Data["Abscissa"]]["Data"];
		}

		if(!$forLegend){
			$SeriesData = $this->Data["Series"];
			$left = array_diff(array_keys($SeriesData), [$this->Data["Abscissa"]]);

			if (count($left) != 1){
				throw pException::PieNoDataSerieException();
			}

			/* Remove unused data clean0Values */
			$Values = array_shift($SeriesData)["Data"];
			$Values = array_values(array_diff($Values, [NULL, 0]));

			/* Gen Palette */
			foreach($Values as $ID => $Value) {
				if(!isset($this->Palette[$ID])){
					$this->Palette[$ID] = new pColor();
				}
			}
		} else {
			$Values = [];
		}

		return [$AbscissaData, $Values, $this->Palette];
	}

	/* Save a palette */
	public function savePalette(array $newPalette)
	{
		foreach($newPalette as $ID => $Color) {
			$this->Palette[$ID] = $Color;
		}
	}

	/* Return the palette of the series */
	public function getPalette()
	{
		return $this->Palette;
	}

	/* Initialize a given scatter serie */
	public function initScatterSerie(int $ID)
	{
		if (isset($this->Data["ScatterSeries"][$ID])) {
			throw pException::InvalidInput("Invalid scatter serie ID");
		}

		$this->Data["ScatterSeries"][$ID] = [
			"Description" => "Scatter " . $ID,
			"isDrawable" => TRUE,
			"Picture" => NULL,
			"Ticks" => NULL,
			"Weight" => NULL,
			"Color" => (isset($this->Palette[$ID])) ? $this->Palette[$ID] : new pColor()
		];
	}

	public function normalize(int $NormalizationFactor = 100, string $UnitChange = "", int $Round = 1)
	{
		$Abscissa = $this->Data["Abscissa"];
		$SelectedSeries = [];
		$MaxVal = 0;
		foreach($this->Data["Axis"] as $AxisID => $Axis) {

			($UnitChange != "") AND $this->Data["Axis"][$AxisID]["Unit"] = $UnitChange;

			foreach($this->Data["Series"] as $SerieName => $Serie) {
				if ($Serie["Axis"] == $AxisID && $Serie["isDrawable"] == TRUE && $SerieName != $Abscissa) {
					$SelectedSeries[$SerieName] = $SerieName;
					if (count($Serie["Data"]) > $MaxVal) {
						$MaxVal = count($Serie["Data"]);
					}
				}
			}
		}

		for ($i = 0; $i < $MaxVal; $i++) {
			$Factor = 0;
			foreach($SelectedSeries as $SerieName) {
				$Value = $this->Data["Series"][$SerieName]["Data"][$i];
				($Value != VOID) AND $Factor += abs($Value);
			}

			if ($Factor != 0) {
				$Factor = $NormalizationFactor / $Factor;
				foreach($SelectedSeries as $SerieName) {
					$Value = $this->Data["Series"][$SerieName]["Data"][$i];
					if ($Value != VOID && $Factor != $NormalizationFactor) {
						$this->Data["Series"][$SerieName]["Data"][$i] = round(abs($Value) * $Factor, $Round);
					} elseif ($Value == VOID || $Value == 0) {
						$this->Data["Series"][$SerieName]["Data"][$i] = VOID;
					} elseif ($Factor == $NormalizationFactor) {
						$this->Data["Series"][$SerieName]["Data"][$i] = $NormalizationFactor;
					}
				}
			}
		}

		foreach($SelectedSeries as $SerieName) {
			$data = array_diff($this->Data["Series"][$SerieName]["Data"],[VOID]);
			$this->Data["Series"][$SerieName]["Max"] = max($data);
			$this->Data["Series"][$SerieName]["Min"] = min($data);
		}
	}

	/* Returns the number of drawable series */
	public function countDrawableSeries()
	{
		$Results = 0;
		foreach($this->Data["Series"] as $SerieName => $Serie) {
			if ($Serie["isDrawable"] && $SerieName != $this->Data["Abscissa"]) {
				$Results++;
			}
		}

		return $Results;
	}

	/* Create a dataset based on a formula */
	public function createFunctionSerie(string $SerieName, \Closure $Function, string $Formula, array $Options = [])
	{
		$MinX = isset($Options["MinX"]) ? $Options["MinX"] : -10;
		$MaxX = isset($Options["MaxX"]) ? $Options["MaxX"] : 10;
		$XStep = isset($Options["XStep"]) ? $Options["XStep"] : 1;
		$AutoDescription = isset($Options["AutoDescription"]) ? $Options["AutoDescription"] : FALSE;
		$RecordAbscissa = isset($Options["RecordAbscissa"]) ? $Options["RecordAbscissa"] : FALSE;
		$AbscissaSerie = isset($Options["AbscissaSerie"]) ? $Options["AbscissaSerie"] : "Abscissa";

		$Result = [];
		$Abscissa = [];

		for ($i = $MinX; $i <= $MaxX; $i = $i + $XStep) {
			$Abscissa[] = $i;
			$ret = $Function($i);
			$Result[] = (in_array("$ret", ["NAN", "INF", "-INF"])) ? VOID : $ret;
		}

		$this->addPoints($Result, $SerieName);

		($AutoDescription) AND $this->setSerieDescription($SerieName, $Formula);
		($RecordAbscissa) AND $this->addPoints($Abscissa, $AbscissaSerie);
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

	public function scaleGetXSettings()
	{
		foreach($this->Data["Axis"] as $Settings) {
			if ($Settings["Identity"] == AXIS_X) {
				return [$Settings["Margin"],$Settings["Rows"]];
			}
		}
	}

	/* Return the data & configuration of the series */
	public function getData()
	{
		return $this->Data;
	}

	/* Called by the scaling algorithm to save the config */
	public function saveAxisConfig(array $Axis)
	{
		$this->Data["Axis"] = $Axis;
	}

	/* Save the Y Margin if set */
	public function saveYMargin(int $Value)
	{
		$this->Data["YMargin"] = $Value;
	}

	/* Called by the scaling algorithm to save the orientation of the scale */
	public function saveOrientation(int $Orientation)
	{
		$this->Data["Orientation"] = $Orientation;
	}

}

?>
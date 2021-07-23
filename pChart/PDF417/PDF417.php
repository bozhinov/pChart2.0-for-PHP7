<?php

namespace pChart\PDF417;

use pChart\PDF417\Encoder\Encoder;
use pChart\pColor;

class PDF417
{
	private $options = [];
	private $myPicture;

	public function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
	}

	public function config(array $options)
	{
		$this->__construct($options);
	}

	private function option_in_range($value, int $start, int $end)
	{
		if (!is_numeric($value) || $value < $start || $value > $end) {
			throw pException::PDF417InvalidInput("Invalid value. Expected an integer between $start and $end.");
		}

		return $value;
	}

	private function validateOptions()
	{
		if (!in_array($this->options["hint"], ["binary", "numbers", "text", "none"])){
			throw pException::PDF417InvalidInput("Invalid value for \"hint\". Expected \"binary\", \"numbers\" or \"text\".");
		}

		if (!($this->options['color'] instanceof pColor)) {
			throw pException::PDF417InvalidInput("Invalid value for \"color\". Expected a pColor object.");
		}

		if (!($this->options['bgColor'] instanceof pColor)) {
			throw pException::PDF417InvalidInput("Invalid value for \"bgColor\". Expected a pColor object.");
		}
	}
	
	public function draw_image($pixelGrid)
	{
		$image = $this->myPicture->gettheImage();
		$padding = $this->options['padding'];

		$width = count($pixelGrid[0]);
		$height = count($pixelGrid);

		$scaleX = $this->options['scale'];
		$scaleY = $this->options['scale'] * $this->options['ratio'];

		// Apply scaling & aspect ratio
		$width = ($width * $scaleX) + $padding * 2;
		$height = ($height * $scaleY) + $padding * 2;

		// Extract options
		$bgColorAlloc = $this->myPicture->allocatepColor($this->options['bgColor']);
		imagefill($image, 0, 0, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($this->options['color']);

		// Render the barcode
		foreach ($pixelGrid as $y => $row) {
			foreach ($row as $x => $value) {
				if ($value) {
					imagefilledrectangle(
						$image,
						($x * $scaleX) + $padding,
						($y * $scaleY) + $padding,
						(($x + 1) * $scaleX - 1) + $padding,
						(($y + 1) * $scaleY - 1) + $padding,
						$colorAlloc
					);
				}
			}
		}
	}

	public function encode($data, array $opts = [])
	{
		$this->options['color'] = (isset($opts['color'])) ? $opts['color'] : new pColor(0);
		$this->options['bgColor'] = (isset($opts['bgColor'])) ? $opts['bgColor'] : new pColor(255);
		/**
		* Number of data columns in the bar code.
		*
		* The total number of columns will be greater due to adding start, stop,
		* left and right columns.
		*/
		$this->options['columns'] = (isset($opts['columns'])) ? $this->option_in_range($opts['columns'], 1, 30) : 6;
		/**
		* Can be used to force binary encoding. This may reduce size of the
		* barcode if the data contains many encoder changes, such as when
		* encoding a compressed file.
		*/
		$this->options['hint'] = (isset($opts['hint'])) ? $opts['hint'] : "none";
		$this->options['scale'] = (isset($opts['scale'])) ? $this->option_in_range($opts['scale'], 1, 20) : 3;
		$this->options['ratio'] = (isset($opts['ratio'])) ? $this->option_in_range($opts['ratio'], 1, 10) : 3;
		$this->options['padding'] = (isset($opts['padding'])) ? $this->option_in_range($opts['padding'], 0, 50) : 20;
		$this->options['quality'] = (isset($opts['quality'])) ? $this->option_in_range($opts['quality'], 0, 100) : 90;
		$this->options['securityLevel'] = (isset($opts['securityLevel'])) ? $this->option_in_range($opts['securityLevel'], 0, 8) : 2;

		$this->validateOptions();

		$pixelGrid = (new Encoder($this->options))->encodeData($data);
		$this->draw_image($pixelGrid);
	}
}

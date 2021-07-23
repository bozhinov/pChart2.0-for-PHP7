<?php

namespace pChart\Aztec;

use pChart\Aztec\Encoder\Encoder;
use pChart\pColor;
use pChart\pException;

class Aztec
{
	private $options = [];
	private $myPicture;

	public function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
	}

	private function setColor($value, $default, $opts)
	{
		if (!isset($opts[$value])) {
			$this->options[$value] = new pColor($default);
		} else {
			if (!($opts[$value] instanceof pColor)) {
				throw pException::AztecInvalidInput("Invalid value for \"$value\". Expected an pColor object.");
			}
			$this->options[$value] = $opts[$value];
		}
	}

	public function config(array $opts)
	{
		$this->__construct($opts);
	}

	private function option_in_range($value, int $start, int $end)
	{
		if (!is_numeric($value) || $value < $start || $value > $end) {
			throw pException::AztecInvalidInput("Invalid value. Expected an integer between $start and $end.");
		}

		return $value;
	}

	private function render($pixelGrid)
	{
		$image = $this->myPicture->gettheImage();
		$width = count($pixelGrid);
		$ratio = $this->options['ratio'];
		$padding = $this->options['padding'];
		#$this->size = ($width * $ratio) + ($padding * 2);

		// Extract options
		$bgColorAlloc = $this->myPicture->allocatepColor($this->options['bgColor']);
		imagefill($image, 0, 0, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($this->options['color']);

		// Render the code
		for ($x = 0; $x < $width; $x++) {
			for ($y = 0; $y < $width; $y++) {
				if (isset($pixelGrid[$x][$y])){
					imagefilledrectangle(
						$image, ($x * $ratio) + $padding,
						($y * $ratio) + $padding,
						(($x + 1) * $ratio - 1) + $padding,
						(($y + 1) * $ratio - 1) + $padding,
						$colorAlloc
					);
				}
			}
		}
	}

	public function encode($data, array $opts = [])
	{
		$this->setColor('color', 0, $opts);
		$this->setColor('bgColor', 255, $opts);

		if (!isset($opts['hint'])) {
			$this->options['hint'] = "dynamic";
		} else {
			if (!in_array($opts['hint'], ["binary", "dynamic"])){
				throw pException::AztecInvalidInput("Invalid value for \"hint\". Expected \"binary\" or \"dynamic\".");
			}
			$this->options['hint'] = $opts['hint'];
		}

		$this->options['ratio'] = (isset($opts['ratio'])) ? $this->option_in_range($opts['ratio'], 1, 10) : 4;
		$this->options['padding'] = (isset($opts['padding'])) ? $this->option_in_range($opts['padding'], 0, 50) : 20;
		$this->options['quality'] = (isset($opts['quality'])) ? $this->option_in_range($opts['quality'], 0, 100) : 90;
		$this->options['eccPercent'] = (isset($opts['eccPercent'])) ? $this->option_in_range($opts['eccPercent'], 1, 200) : 33;
		$pixelGrid = (new Encoder())->encode($data, $this->options['eccPercent'], $this->options["hint"]);

		$this->render($pixelGrid);

	}
}

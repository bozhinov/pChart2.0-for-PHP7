<?php
/*
 * PHP QR Code
 * Last update - 31.12.2019
 */

namespace pChart\QRCode;

use pChart\QRCode\Encoder\Encoder;
use pChart\pException;
use pChart\pColor;

class QRCode {

	private $options;
	private $myPicture;

	function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
	}

	public function config(array $opts)
	{
		$this->__construct($opts);
	}

	private function setColor($value, $default, $opts)
	{
		if (!isset($opts[$value])) {
			$this->options[$value] = new pColor($default);
		} else {
			if (!($opts[$value] instanceof pColor)) {
				throw pException::QRCodeInvalidInput("Invalid value for \"$value\". Expected an azColor object.");
			}
			$this->options[$value] = $opts[$value];
		}
	}

	private function option_in_range($value, int $start, int $end)
	{
		if (!is_numeric($value) || $value < $start || $value > $end) {
			throw pException::QRCodeInvalidInput("Invalid value. Expected an integer between $start and $end.");
		}

		return $value;
	}

	public function encode(string $text, array $opts = [], string $hint = "undefined")
	{
		$this->setColor('color', 0, $opts);
		$this->setColor('bgColor', 255, $opts);

		if (isset($opts['level'])){
			switch(strtoupper($opts['level'])){
				case "L":
					$this->options['level'] = 0;
					break;
				case "M":
					$this->options['level'] = 1;
					break;
				case "Q":
					$this->options['level'] = 2;
					break;
				case "H":
					$this->options['level'] = 3;
					break;
				default:
					throw pException::QRCodeInvalidInput("Invalid value for \"level\"");
			}
		} else {
			$this->options['level'] = 0;
		}
		$this->options['size'] = (isset($opts['size'])) ? $this->option_in_range($opts['size'], 0, 20) : 3;
		$this->options['margin'] = (isset($opts['margin'])) ? $this->option_in_range($opts['margin'], 0, 20) : 4;

		if($text == '\0' || $text == '') {
			throw pException::QRCodeInvalidInput('empty string!');
		}

		switch(strtolower($hint)){
			case "undefined":
				$hint = -1;
				break;
			case "numeric":
				$hint = 0;
				break;
			case "alphanumeric":
				$hint = 1;
				break;
			case "byte":
				$hint = 2;
				break;
			case "kanji":
				$hint = 3;
				break;
				default:
					throw pException::QRCodeInvalidInput("Invalid value for \"hint\"");
		}

		$encoded = (new Encoder($this->options['level']))->encodeString($text, $hint);
		$renderer = new Renderer($encoded, $this->options)->draw_image($this->myPicture);
	}
}

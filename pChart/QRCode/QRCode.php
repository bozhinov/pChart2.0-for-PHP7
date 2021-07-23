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
				throw pException::QRCodeInvalidInput("Invalid value for \"$value\". Expected an pColor object.");
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

	public function draw_image($encoded)
	{
		$h = count($encoded);
		$imgH = $h + 2 * $this->options['margin'];

		$base_image = imagecreate($imgH, $imgH);

		// Extract options
		list($R, $G, $B) = $this->options['bgColor']->get();
		$bgColorAlloc = imagecolorallocate($base_image, $R, $G, $B);
		list($R, $G, $B) = $this->options['color']->get();
		$colorAlloc = imagecolorallocate($base_image, $R, $G, $B);

		imagefill($base_image, 0, 0, $bgColorAlloc);

		for($y = 0; $y < $h; $y++) {
			for($x = 0; $x < $h; $x++) {
				if ($encoded[$y][$x] & 1) {
					imagesetpixel($base_image, $x + $this->options['margin'], $y + $this->options['margin'], $colorAlloc);
				}
			}
		}

		$pixelPerPoint = min($this->options['size'], $imgH);
		$target_h = $imgH * $pixelPerPoint;

		$image = $this->myPicture->gettheImage();
		imagecopyresized($image, $base_image, 0, 0, 0, 0, $target_h, $target_h, $imgH, $imgH);
		imagedestroy($base_image);
	}

	public function encode(string $text, array $opts = [])
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

		if (isset($opts['hint'])){
			switch(strtolower($opts['hint'])){
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
		} else {
			$hint = -1;
		}

		$encoded = (new Encoder($this->options['level']))->encodeString($text, $hint);
		$this->draw_image($encoded);
	}
}

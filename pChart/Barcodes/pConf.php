<?php
/*
pConf - class to help unify the different barcode libs config

Version     : 2.4.0-dev
Made by     : Momchil Bozhinov
Last Update : 27/07/2021

This file can be distributed under MIT
*/

namespace pChart\Barcodes;

use pChart\pColor;
use pChart\pException;

class pConf {

	protected $options = ['StartX' => 0, 'StartY' => 0];

	public function set_start_position(int $x, int $y)
	{
		$this->options['StartX'] = $x;
		$this->options['StartY'] = $y;
	}

	public function apply_user_options(array $opts, array $defaults)
	{
		$this->options += array_replace_recursive($defaults, $opts);
		$this->set_color('bgColor', 255);
		$this->set_color('color', 0);
	}

	public function set_color(string $value, int $default)
	{
		if (!isset($this->options['palette'][$value])) {
			$this->options['palette'][$value] = new pColor($default);
		} else {
			if (!($this->options['palette'][$value] instanceof pColor)) {
				throw pException::InvalidInput("Invalid value for $value. Expected an pColor object.");
			}
		}
	}

	public function return_key_if_found(string $val, array $possibilities)
	{
		$ret = array_search($val, $possibilities);
		if ($ret === FALSE){
			throw pException::InvalidInput("Invalid value specified.");
		}

		return $ret;
	}

	public function check_text_valid($text)
	{
		if($text == '\0' || $text == '') {
			throw pException::InvalidInput("Invalid value for text");
		}
	}

	public function check_range(string $val, int $start, int $end)
	{
		$ret = $this->options[$val];
		if (!is_numeric($ret) || $ret < $start || $ret > $end) {
			throw pException::InvalidInput("Invalid value. Expected an integer between $start and $end.");
		}
	}
}
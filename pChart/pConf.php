<?php
/*
pConf - class to help unify the different barcode libs config

Version     : 2.4.0-dev
Made by     : Momchil Bozhinov
Last Update : 27/07/2021

This file can be distributed under MIT
*/

namespace pChart;

use pChart\pColor;
use pChart\pException;

class pConf {

	private $options = ['StartX' => 0, 'StartY' => 0];
	private $user_options = [];

	public function set_start_position(int $x, int $y)
	{
		$this->options['StartX'] = $x;
		$this->options['StartY'] = $y;
	}

	public function apply_user_options(array $opts)
	{
		$this->user_options = $opts;

		$this->set_color('color', 0);
		$this->set_color('bgColor', 255);
	}

	public function set(string $opt, $val)
	{
		$this->options[$opt] = $val;
	}

	public function get(string $opt)
	{
		return $this->options[$opt];
	}

	public function set_color(string $value, int $default)
	{
		if (!isset($this->user_options[$value])) {
			$this->options[$value] = new pColor($default);
		} else {
			if (!($this->user_options[$value] instanceof pColor)) {
				throw pException::InvalidInput("Invalid value for $value. Expected an pColor object.");
			}
			$this->options[$value] = $this->user_options[$value];
		}
	}

	public function return_if_match_or_default(string $val, array $possibilities, string $default)
	{
		if (isset($this->user_options[$val])) {
			$ret = $this->user_options[$val];
			if (!in_array($ret, $possibilities)){
				throw pException::InvalidInput("Invalid value for $val.");
			}
		} else {
			$ret = $default;
		}
		return $ret;
	}

	public function set_if_within_range_or_default(string $val, int $start, int $end, int $default)
	{
		$this->options[$val] = $this->return_if_within_range_or_default($val, $start, $end, $default);
	}

	public function return_if_within_range_or_default(string $val, int $start, int $end, int $default)
	{
		if (isset($this->user_options[$val])) {
			$ret = $this->user_options[$val];
			if (!is_numeric($ret) || $ret < $start || $ret > $end) {
				throw pException::InvalidInput("Invalid value. Expected an integer between $start and $end.");
			}
		} else {
			$ret = $default;
		}
		return $ret;
	}
}
<?php

namespace pChart;

use pChart\pColor;
use pChart\pException;

class pConf {

	private $options = [];
	private $user_options = [];

	public function apply_user_options(array $opts)
	{
		$this->user_options = $opts;

		$this->setColor('color', 0);
		$this->setColor('bgColor', 255);
	}

	public function set(string $opt, $val)
	{
		$this->options[$opt] = $val;
	}

	public function get(string $opt)
	{
		return $this->options[$opt];
	}

	public function setColor(string $value, int $default)
	{
		if (!isset($this->user_options[$value])) {
			$this->options[$value] = new pColor($default);
		} else {
			if (!($this->user_options[$value] instanceof pColor)) {
				throw pException::InvalidInput("Invalid value for \"$value\". Expected an pColor object.");
			}
			$this->options[$value] = $this->user_options[$value];
		}
	}

	public function return_if_match_or_default(string $val, array $possibilities, string $default)
	{
		if (isset($this->user_options[$val])) {
			if (!in_array($this->user_options[$val], $possibilities)){
				throw pException::InvalidInput("Invalid value for $val.");
			}
			$ret = $this->user_options[$val];
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
			if (!is_numeric($val) || $val < $start || $val > $end) {
				throw pException::InvalidInput("Invalid value. Expected an integer between $start and $end.");
			}
			$ret = $this->user_options[$val];
		} else {
			$ret = $default;
		}
		return $ret;
	}
}
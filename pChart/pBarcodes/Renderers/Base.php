<?php

namespace pChart\pBarcodes\Renderers;

class Base {

	protected $config;
	protected $code;
	protected $widths;
	protected $myPicture;

	public function configure($config)
	{
		$this->widths = array_values($config['widths']);
		$this->config = $config;
	}

	public function use_image($MyPicture, $code)
	{
		$this->myPicture = $MyPicture;
		$this->code = $code;

		list($width, $height, $x, $y, $w, $h) = $this->calculate_size_ext();

		$this->render_image($x, $y, $w, $h);
	}

	private function calculate_size_ext()
	{
		$size = $this->calculate_size();

		$left = $this->config['padding']['Left'];
		$top = $this->config['padding']['Top'];
		$dwidth  = ceil($size[0] * $this->config['scale']['Horizontal']) + $left + $this->config['padding']['Right'];
		$dheight = ceil($size[1] * $this->config['scale']['Vertial']) + $top + $this->config['padding']['Bottom'];
		$iwidth  = (!is_null($this->config['Width']))  ? $this->config['Width']  : $dwidth;
		$iheight = (!is_null($this->config['Height'])) ? $this->config['Height'] : $dheight;
		$swidth  = $iwidth - $left - $this->config['padding']['Right'];
		$sheight = $iheight - $top - $this->config['padding']['Bottom'];

		return [$iwidth, $iheight, $left, $top, $swidth, $sheight];
	}

}

?>
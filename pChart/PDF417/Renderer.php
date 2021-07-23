<?php

namespace pChart\PDF417;

class Renderer
{
	private $image;
	private $pixelGrid;
	private $options;

	function __construct(array $pixelGrid, array $options)
	{
		$this->pixelGrid = $pixelGrid;
		$this->options = $options;
	}

	public function draw_image($myPicture)
	{
		$image = $myPicture->gettheImage();
		$padding = $this->options['padding'];

		$width = count($this->pixelGrid[0]);
		$height = count($this->pixelGrid);

		$scaleX = $this->options['scale'];
		$scaleY = $this->options['scale'] * $this->options['ratio'];

		// Apply scaling & aspect ratio
		$width = ($width * $scaleX) + $padding * 2;
		$height = ($height * $scaleY) + $padding * 2;

		// Extract options
		$bgColorAlloc = $myPicture->allocatepColor($this->options['bgColor']);
		imagefill($image, 0, 0, $bgColorAlloc);
		$colorAlloc = $myPicture->allocatepColor($this->options['color']);

		// Render the barcode
		foreach ($this->pixelGrid as $y => $row) {
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
}

<?php

namespace Slonyaka\Market\Chart;

use Slonyaka\Market\Svg\Path;
use Slonyaka\Market\Svg\Svg;

class Line implements Chart{

	private $data;

	private $width = 400;
	private $height = 400;
	private $offset = 20;
	private $priceType = 'closePrice';
	private $styles = __DIR__ . '/../assets/line.css';

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function setStyles(string $path)
	{
		$this->styles = $path;
	}

	public function setWidth(int $width)
	{
		$this->width = $width;
	}

	public function setHeight(int $height)
	{
		$this->height = $height;
	}

	public function setOffset(int $offset)
	{
		$this->offset = $offset;
	}

	public function setPriceType(string $priceType)
	{
		$this->priceType = $priceType;
	}

	public function build() {

		$svg = new Svg($this->width, $this->height);
		$path = new Path();
		$pathOutline = new Path();

		$height = $this->height - $this->offset;

		$min = $this->getMin();
		$max = $this->getMax();

		$this->addLimits($svg, $min, $max);

		$interval = ($this->width - $this->offset *2) / (count($this->data) - 1);

		$verticalPoint = ($max - $min) / ($this->height - $this->offset);

		foreach ($this->data as $index => $item) {

			$x = $index * $interval;
			$y = $height - (($item[$this->priceType] - $min) / $verticalPoint);

			$labelPosition = $index * $interval;

			if ($index !== 0) {
				$labelPosition -= $this->offset;
			}

			$svg->addLabel($item['time'],$labelPosition, $this->height );

			if ($index == 0) {

				$path->setStart($x + $this->offset, $y + $this->offset);
				$pathOutline->setStart($x + $this->offset, $y+2  + $this->offset);
			} else {

				$path->addPoint($x, $y + $this->offset);
				$pathOutline->addPoint($x - 2, $y+2  + $this->offset);
			}
		}

		$path->close($height, $this->offset);

		$svg->setPaths([$path, $pathOutline]);

		return $this->styles() . $svg->draw();
	}

	protected function styles()
	{
		$output = '<style>';
		$output .= file_get_contents($this->styles);
		$output .= '</style>';

		return $output;
	}

	protected function getMin() {

		$min = null;

		foreach($this->data as $item) {
			if ($min === null || $min > $item['lowPrice']) {
				$min = $item['lowPrice'];
			}
		}

		return $min;
	}

	protected function getMax() {

		$max = null;

		foreach($this->data as $item) {
			if ($max === null || $max < $item['highPrice']) {
				$max = $item['highPrice'];
			}
		}

		return $max;
	}

	protected function addLimits(Svg $svg, $min, $max)
	{
		$width = $this->width - $this->offset;
		$height = $this->height - $this->offset;

		$svg->addLabel($min, $width, $this->height - $this->offset);
		$svg->addLabel($max, $width, $this->offset);

		$svg->addHorizontalLine($width, $height, 'min-line', 4);
		$svg->addHorizontalLine($width, $this->offset, 'max-line', 4);
	}
}
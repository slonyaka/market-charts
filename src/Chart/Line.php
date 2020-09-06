<?php

namespace Slonyaka\Market\Chart;

use Slonyaka\Market\Svg\Path;
use Slonyaka\Market\Svg\Svg;

class Line implements Chart
{

	private $data;

	private $width = 400;
	private $height = 400;
	private $offset = 20;
	private $priceType = 'closePrice';
	private $styles = __DIR__ . '/../assets/line.css';
	private $period;

	public function __construct($data)
	{
		if (!empty($data[1]) && $data[1]['time'] < $data[0]['time']) {
			$data = array_reverse($data);
		}
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

	public function setPeriod(string $period)
	{
		$this->period = $period;
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

		$verticalPoint = ($max - $min) / $height;

		foreach ($this->data as $index => $item) {

			$x = $index * $interval;
			$y = $this->offset + $height - (($item[$this->priceType] - $min) / $verticalPoint);

			if ($index == 0) {

				$path->setStart($x, $y);
				$pathOutline->setStart($x, $y + 2);
				continue;
			} else {

				$path->addPoint($x, $y);
				$pathOutline->addPoint($x , $y + 2);
			}

			if ($index == count($this->data) - 1) {
				$this->addLastPrice($svg, $x , $y + 2, $item[$this->priceType]);
			}

			if ($index % 6 == 0) {
				$svg->addLabel($item['time'], $x, $this->height );
				$svg->addVerticalLine($x, $height,'grid-line');
			}
		}

		$path->close($height, 0);

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

	protected function getMin()
	{

		$min = null;

		foreach($this->data as $item) {
			if ($min === null || $min > $item['lowPrice']) {
				$min = $item['lowPrice'];
			}
		}

		return $min;
	}

	protected function getMax()
	{

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

		$svg->addLabel($min, $width, $height);
		$svg->addLabel($max, $width, $this->offset);

		$svg->addHorizontalLine($width, $height, 'min-line', 4);
		$svg->addHorizontalLine($width, $this->offset, 'max-line', 4);
	}


	private function addLastPrice(Svg $svg, $x, $y, $value)
	{
		$width = $this->width - $this->offset;

		$svg->addLabel($value, $width, $y );
		$svg->addLine($x, $y,$width, $y, 'current-line');
	}
}
<?php

namespace Slonyaka\Market\Chart;

use Slonyaka\Market\Collection;
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
	private $period = 6;

	public function __construct(Collection $data)
	{
		$this->data = $data;
	}

	public function setStyles(string $path)
	{
		$this->styles = $path;
		return $this;
	}

	public function setWidth(int $width)
	{
		$this->width = $width;
		return $this;
	}

	public function setHeight(int $height)
	{
		$this->height = $height;
		return $this;
	}

	public function setOffset(int $offset)
	{
		$this->offset = $offset;
		return $this;
	}

	public function setPriceType(string $priceType)
	{
		$this->priceType = $priceType;
		return $this;
	}

	public function setPeriod(int $period)
	{
		$this->period = $period;
		return $this;
	}

	public function build()
	{

		$svg = new Svg($this->width, $this->height);
		$path = new Path();
		$pathOutline = new Path();

		$height = $this->height - $this->offset;

		$min = $this->getMin();
		$max = $this->getMax();

		$this->addLimits($svg, $min, $max);

		$interval = ($this->width - $this->offset *2) / ($this->data->count() - 1);

		$verticalPoint = ($max - $min) / $height;

		foreach ($this->data->readfromEnd() as $index => $item) {

			$x = $index * $interval;
			$y = $this->height - (($item->{$this->priceType} - $min) / $verticalPoint);
			$y2 = $y + 2;

			if ($this->isPeriod($index)) {

				if ($index == 0) {
					$xPos = $x;
				} else {
					$xPos = $x - $this->offset;
				}

				$svg->addLabel($item->time, $xPos, $this->height );
				$svg->addVerticalLine($x, $height,'grid-line');
			}

			if ($index == 0) {
				$path->setStart($x, $y);
				$pathOutline->setStart($x, $y2);
				continue;
			} else {
				$path->addPoint($x, $y);
				$pathOutline->addPoint($x , $y2);
			}

			if ($this->isLastItem($index)) {
				$this->addLastPrice($svg, $x , $y2, $item->{$this->priceType});
			}
		}

		$path->close($height);

		return $this->styles() . $svg->setPaths([$path, $pathOutline])->draw();
	}

	protected function styles()
	{
		return '<style>'. @file_get_contents($this->styles) .'</style>';
	}

	protected function getMin()
	{

		$min = null;

		foreach($this->data->readfromEnd() as $item) {
			if ($min === null || $min > $item->lowPrice) {
				$min = $item->lowPrice;
			}
		}

		return $min;
	}

	protected function getMax()
	{

		$max = null;

		foreach($this->data->readfromEnd() as $item) {
			if ($max === null || $max < $item->highPrice) {
				$max = $item->highPrice;
			}
		}

		return $max;
	}

	protected function addLimits(Svg $svg, $min, $max)
	{
		$width = $this->width - $this->offset;
		$height = $this->height - $this->offset;

		$svg->addLabel($min, $width - $this->offset, $height);
		$svg->addLabel($max, $width - $this->offset, $this->offset);

		$svg->addHorizontalLine($width, $height, 'min-line', 4);
		$svg->addHorizontalLine($width, $this->offset, 'max-line', 4);
	}

	protected function isLastItem($index)
	{
		return $index == $this->data->count() - 1;
	}

	protected function isPeriod($index)
	{
		return $index % ($this->period - 1) == 0;
	}

	protected function addLastPrice(Svg $svg, $x, $y, $value)
	{
		$width = $this->width - $this->offset;

		$svg->addLabel($value, $width - $this->offset, $y );
		$svg->addLine($x, $y,$width - $this->offset, $y, 'current-line');
	}
}
<?php

namespace Slonyaka\Market\Chart;


use Slonyaka\Market\Svg\Path;
use Slonyaka\Market\Svg\Svg;

class Line extends Chart
{
	public function build()
	{
		$svg = new Svg($this->width, $this->height);
		$path = new Path();
		$pathOutline = new Path();

		$min = $this->getMin();
		$max = $this->getMax();

		$this->addLimits($svg, $min, $max);

		$interval = ($this->width - $this->offset *2) / ($this->data->count() - 1);

		$verticalPoint = ($max - $min) / $this->innerHeight;

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
				$svg->addVerticalLine($x, $this->innerHeight,'grid-line');
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

		$path->close($this->innerHeight);

		return $this->styles() . $svg->setPaths([$path, $pathOutline])->draw();
	}

	protected function addLastPrice(Svg $svg, $x, $y, $value)
	{
		$width = $this->width - $this->offset;

		$svg->addLabel($value, $width - $this->offset, $y );
		$svg->addLine($x, $y,$width - $this->offset, $y, 'current-line');
	}
}
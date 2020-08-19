<?php

namespace Slonyaka\Market\Chart;

use Slonyaka\Market\Svg\Path;
use Slonyaka\Market\Svg\Svg;

class Line implements Chart{

	private $data;

	private $width = 400;
	private $height = 400;
	private $offset = 20;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function build() {

		$svg = new Svg($this->width, $this->height);

		$width = $this->width - $this->offset;
		$height = $this->height - $this->offset;

		$min = $this->getMin();
		$max = $this->getMax();

		$interval = ($this->width - $this->offset *2) / (count($this->data) - 1);

		$path = new Path();
		$pathOutline = new Path();

		$svg->addLabel($min, $width, $this->height - $this->offset);
		$svg->addLabel($max, $width, $this->offset);

		$svg->addHorizontalLine($width, $height, 'min-line');
		$svg->addHorizontalLine($width, $this->offset, 'max-line');

		$verticalPoint = ($max - $min) / ($this->height - $this->offset);


		foreach ($this->data as $index => $item) {

			$x = $index * $interval;
			$y = $height - (($item['closePrice'] - $min) / $verticalPoint);

			$labelPosition = ($index * $interval) - $this->offset;

			if ($index == 0) {
				$labelPosition = 0;
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

		return $svg->draw();
	}

	private function getMin() {

		$min = null;

		foreach($this->data as $item) {
			if ($min === null || $min > $item['lowPrice']) {
				$min = $item['lowPrice'];
			}
		}

		return $min;
	}

	private function getMax() {
		$max = null;

		foreach($this->data as $item) {
			if ($max === null || $max < $item['highPrice']) {
				$max = $item['highPrice'];
			}
		}

		return $max;
	}


}
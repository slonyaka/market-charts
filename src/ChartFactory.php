<?php

declare(strict_types=1);

namespace Slonyaka\Market;


use Slonyaka\Market\Chart\Line;

class ChartFactory {

	public function createLine($data)
	{
		return new Line($data);
	}

}
<?php

declare(strict_types=1);

namespace Slonyaka\Market;


use Slonyaka\Market\Chart\Line;

class ChartLoader {

	public function createLine($data)
	{
		return new Line($data);
	}

}
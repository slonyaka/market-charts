<?php

declare(strict_types=1);

namespace Slonyaka\Market;


use Slonyaka\Market\Chart\Candle;
use Slonyaka\Market\Chart\Line;

class ChartFactory
{

    public function createLine(Collection $data): Line
    {
        return new Line($data);
    }

    public function createCandle(Collection $data): Candle
    {
        return new Candle($data);
    }

}
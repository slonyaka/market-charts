<?php


namespace Slonyaka\Market\Chart;


use Slonyaka\Market\Svg\Path;
use Slonyaka\Market\Svg\Svg;

class Candle extends Chart
{

    protected $paths = [];

    public function build()
    {
        $this->setStyles(__DIR__ . '/../assets/candle.css');

        $svg = new Svg($this->width, $this->height);

        $min = $this->getMin();
        $max = $this->getMax();

        $this->addLimits($svg, $min, $max);

        $interval = ($this->width - $this->offset * 2) / ($this->data->count() - 1);

        $verticalPoint = ($max - $min) / $this->innerHeight;

        foreach ($this->data->readfromEnd() as $index => $item) {


            if ($item->openPrice > $item->closePrice) {
                $topPrice = $item->openPrice;
                $bottomPrice = $item->closePrice;
                $color = 'black';
            } else {
                $topPrice = $item->closePrice;
                $bottomPrice = $item->openPrice;
                $color = 'white';
            }

            $x = $index * $interval;

            $highY = $this->height - (($item->highPrice - $min) / $verticalPoint);
            $topY = $this->height - (($topPrice - $min) / $verticalPoint);
            $bottomY = $this->height - (($bottomPrice - $min) / $verticalPoint);
            $lowY = $this->height - (($item->lowPrice - $min) / $verticalPoint);

            if ($this->isPeriod($index)) {

                if ($index == 0) {
                    $xPos = $x;
                } else {
                    $xPos = $x - $this->offset;
                }

                $svg->addLabel($item->time, $xPos, $this->height );
                $svg->addVerticalLine($x, $this->innerHeight,'grid-line');
            }

            $path = new Path();

            $path->setCssClass($color);

            $path->setStart($x, $highY);
            $path->addPoint($x, $topY);
            $path->addPoint($x + 2, $topY);
            $path->addPoint($x + 2, $bottomY);
            $path->addPoint($x, $bottomY);
            $path->addPoint($x, $lowY);
            $path->addPoint($x, $bottomY);
            $path->addPoint($x - 2, $bottomY);
            $path->addPoint($x - 2, $topY);
            $path->addPoint($x, $topY);
            $path->addPoint($x, $highY);

            $this->paths[] = $path;

            if ($this->isLastItem($index)) {
                $y = $this->height - (($item->{$this->priceType} - $min) / $verticalPoint);
                $this->addLastPrice($svg, $x , $y, $item->{$this->priceType});
            }
        }

        return $this->styles() . $svg->setPaths($this->paths)->draw();
    }

    protected function addLastPrice(Svg $svg, $x, $y, $value)
    {
        $width = $this->width - $this->offset;
        $svg->addLabel($value, $width - $this->offset + 4, $y );
    }
}
<?php


namespace Slonyaka\Market\Chart;


use Slonyaka\Market\Collection;
use Slonyaka\Market\Svg\Svg;

abstract class Chart
{

    protected $data;

    protected $width = 400;

    protected $height = 400;

    protected $offset = 20;

    protected $innerHeight = 360;

    protected $priceType = 'closePrice';

    protected $styles = __DIR__ . '/../assets/line.css';

    protected $period = 6;

    protected $indicators = [];

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
        $this->innerHeight = $height - $this->offset;
        return $this;
    }

    public function setOffset(int $offset)
    {
        $this->offset = $offset;
        $this->innerHeight = $this->height - $offset;
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

    public function addIndicator(Indicator $indicator)
    {
        $this->indicators[] = $indicator;
    }

    protected function styles()
    {
        return '<style>' . @file_get_contents($this->styles) . '</style>';
    }

    protected function getMin()
    {

        $min = null;

        foreach ($this->data->reverseRead() as $item) {
            if ($min === null || $min > $item->lowPrice) {
                $min = $item->lowPrice;
            }
        }

        return $min;
    }

    protected function getMax()
    {

        $max = null;

        foreach ($this->data->reverseRead() as $item) {
            if ($max === null || $max < $item->highPrice) {
                $max = $item->highPrice;
            }
        }

        return $max;
    }

    protected function addLimits(Svg $svg, $min, $max)
    {
        $width = $this->width - $this->offset;

        $svg->addLabel($min, $width - $this->offset, $this->innerHeight);
        $svg->addLabel($max, $width - $this->offset, $this->offset);

        $svg->addHorizontalLine($width, $this->innerHeight, 'min-line', 4);
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


    abstract public function build();

    abstract protected function addLastPrice(Svg $svg, $x, $y, $value);

}
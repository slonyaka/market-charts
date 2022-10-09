<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17.08.2020
 * Time: 23:32
 */

namespace Slonyaka\Market\Svg;


class Path
{

    private $start;

    private $points;

    private $lastPoint;

    private $mask;

    private $cssClass = '';

    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
    }

    public function setStart($x, $y)
    {
        $this->start = 'M' . $x . ',' . $y;
    }

    public function addPoint($x, $y)
    {
        $this->lastPoint = [$x, $y];
        $this->points .= ' L ' . $x . ',' . $y;
    }

    public function close($height, $start = 0)
    {
        $this->points .= ' L' . $this->lastPoint[0] . ',' . $height . ' L' . $start . ',' . $height . ' Z';
    }

    public function addMask($mask)
    {
        $this->mask = $mask;
    }

    public function draw()
    {
        $path = '<path class="' . $this->cssClass . '" d="' . $this->start . ' ' . $this->points . '"';

        if (!empty($this->mask)) {
            $path .= 'mask="' . $this->mask . '"';
        }

        $path .= '></path>';

        return $path;
    }

}
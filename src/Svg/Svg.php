<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17.08.2020
 * Time: 21:52
 */

namespace Slonyaka\Market\Svg;


class Svg {

	private $width;
	private $height;
	private $paths;
	private $labels;
	private $lines;

	public function __construct($width, $height) {
		$this->width = $width;
		$this->height = $height;
	}

	public function setPaths(array $paths)
	{
		$this->paths = $paths;
		return $this;
	}

	public function draw()
	{
		$svg = $this->head();
		$svg .= $this->start($this->width, $this->height);
//		$svg .= Svg::defs();
		$svg .= $this->startG();

		foreach ($this->paths as $path) {
			$svg .= $this->path($path);
		}

		foreach ($this->labels as $label) {
			$svg .= $label;
		}

		foreach ($this->lines as $line) {
			$svg .= $line;
		}

		$svg .= $this->finishG();

		$svg .= $this->finish();

		return $svg;
	}


	public function head()
	{
		return '<?xml version="1.0" encoding="UTF-8" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN""http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';
	}

	public function start($width, $height)
	{
		$tag = '<svg version = "1.1" baseProfile="full" xmlns = "http://www.w3.org/2000/svg"  xmlns:xlink = "http://www.w3.org/1999/xlink" xmlns:ev = "http://www.w3.org/2001/xml-events"';
		$tag .= 'height = "'. $height .'px"  width = "'. $width.'px">';

        return $tag;
	}

	public function addLabel($value, $x, $y)
	{
		$this->labels[] = '<text x="'. $x .'" y="'. $y .'">'. $value .'</text>';
	}

	public function addLine($x1, $y1, $x2, $y2, $class = 'line')
	{
		$this->lines[] = '<line x1="'. $x1 .'" y1="'. $y1 .'" x2="'. $x2 .'" y2="'. $y2 .'" class="'. $class .'" stroke="black" />';
	}

	public function addVerticalLine($x, $y, $class = 'v-line')
	{
		$this->addLine($x, 0, $x, $y, $class);
	}

	public function addHorizontalLine($x, $y, $class = 'h-line')
	{
		$this->addLine(0, $y, $x, $y, $class);
	}

	public function defs()
	{
		$defs = '<defs xmlns="http://www.w3.org/2000/svg">
					<mask id="up-mask">
					<rect id="up-clipper" x="0" y="0" width="400" height="300" stroke-width="0" stroke="green" fill="white"></rect>
					</mask>
					</defs>';

		return $defs;
	}

	public function startG()
	{
		return '<g>';
	}

	public function finishG()
	{
		return '</g>';
	}

	public function finish()
	{
		return '</svg>';
	}

	public function path(Path $path)
	{
		return $path->draw();
	}

}
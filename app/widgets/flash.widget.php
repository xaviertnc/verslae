<?php

/*
 *
 * FLASH WIDGET CLASS
 * By: C. Moller - 20 Feb 2016
 *
 * Requires: app.js - Flash
 *
 */

class FlashWidget {

	public $flashitems;
	public $types;


	public function __construct()
	{
		$this->flashitems = State::getFlash('all');
		$this->types = array_keys($this->flashitems);

		Scripts::addLocalScriptsLate('$("#flash").delay(3000).fadeOut(3000);');

		State::delFlash();
	}


	public function typegroup($type)
	{
		return is_array($this->flashitems[$type]) ? $this->flashitems[$type] : array($this->flashitems[$type]);
	}


	protected function render($flashitems)
	{
		// FLASH WIDGET TEMPLATE
		$html = '<section id="flash">' . PHP_EOL;

		$indent = 1;
		foreach ($this->types as $type)
		{
			foreach ($this->typegroup($type) as $index=>$flash)
			{
				$html .= indent($indent) . '<div id="flash_'.$type.'_'.$index.'" class="flash '.$type.'">' . PHP_EOL;

				$indent++;
				$html .= indent($indent) . '<span class="close-x '.$type.'" onclick="Flash.dismiss(\''.$type.'\',\''.$index.'\')">X</span>' . PHP_EOL;
				$html .= indent($indent) . $flash . '<br>' . PHP_EOL;

				$indent--;
				$html .= indent($indent) . '</div>' . PHP_EOL;
			}
		}

		$html .= indent($indent) . '</section>' . PHP_EOL;
		return $html;
	}


	public function __toString()
	{
		return $this->render($this->flashitems);
	}

}

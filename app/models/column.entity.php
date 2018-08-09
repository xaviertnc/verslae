<?php

/*
 *
 * LIST COLUMN ENTITY
 * By: C. Moller - 06 May 2016
 *
 */

class ColumnEntity
{

	public $name;
	public $title;
	public $formatter;

	public function __construct($name, $title = null, $formatter = null)
	{
		if ($name == '#')
		{
			$title = 'No.';
			$formatter = function($row_index, $row_data) { return $row_index + 1; };
		}

		$this->name = $name;
		$this->title = $title?:$name;
		$this->formatter = $formatter?:function($row_index, $row_data) use($name) { return $row_data->$name; };
	}


	public function render($row_index, $row_data)
	{
		return call_user_func($this->formatter, $row_index, $row_data);
	}

}

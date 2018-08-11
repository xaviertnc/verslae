<?php

/*
 *
 * BORGVERSLAG MODEL
 * By: C. Moller - 06 May 2016
 *
 * Updated: 11 Aug 2018
 *  - Nuwe naam
 *
 */

class BorgverslagModel
{
	public static $itemspp;
	public static $itemcount;
	public static $tailcount;
	public static $pagecount;
	public static $orderby;
	public static $ekspo_id;
	public static $columns = [];


	public static function csvInit($itemspp = 1000)
	{
		self::$itemspp = $itemspp;
		self::$ekspo_id = Request::get('ekspo', __HUIDIGE_EKSPO_ID__);
		self::$itemcount = RegistrasiesRepo::kryAantalBorgRegistrasies(self::$ekspo_id);
		self::$tailcount = self::$itemcount % $itemspp;
		self::$pagecount = floor(self::$itemcount / $itemspp);
		self::$orderby = Ui::$sort_widget->orderby();

		self::$columns[] = new ColumnEntity('#');
		self::$columns[] = new ColumnEntity('registrasiedatum' , 'Datum'	, function($i, $row_data) { return Fmt::limit($row_data->registrasiedatum, 11, ''); });
		self::$columns[] = new ColumnEntity('volleNaam'			   , 'Besoeker');
		self::$columns[] = new ColumnEntity('selfoon'			     , 'Selfoon', function($i, $row_data) { return str_replace(' ', '', $row_data->selfoon?:$row_data->telefoon); });
		self::$columns[] = new ColumnEntity('epos'				     , 'Epos'	);
		self::$columns[] = new ColumnEntity('borgkanmykontak'  , 'Kontak'	, function($i, $row_data) { return $row_data->borgkanmykontak?:0; });
		self::$columns[] = new ColumnEntity('reedsborglid'	   , 'Lid'		, function($i, $row_data) { return empty($row_data->reedsborglid)?:0; });

	}


	public static function findColumn($column_name)
	{
		foreach (self::$columns as $column)
		{
			if ($column->name == $column_name) return $column;
		}
	}


	public static function csvGetColumns($keep = null, $remove = null)
	{
		$columns = self::$columns;
		if ($keep) { $columns = array_filter($columns, function($v) use ($keep) { return in_array($v->name, $keep); }); }
		if ($remove) { $columns = array_filter($columns, function($v) use ($remove) { return ! in_array($v->name, $remove); }); }
		//Log::debug(print_r($columns, true));
		return $columns;
	}


	public static function csvGetPageData($limit)
	{
		//Log::debug($limit);
		return RegistrasiesRepo::lysBorgRegistrasies(self::$ekspo_id, $limit, self::$orderby);
	}


	public static function csvGetLine($lineno, $line_data)
	{
		$line = [];
		foreach (self::$columns as $i => $column)
		{
			if ($i==0) { $line[] = self::$columns[0]->render($lineno, null); continue; }
			$line[] = $column->render($lineno, $line_data);
		}
		return $line;
	}

}

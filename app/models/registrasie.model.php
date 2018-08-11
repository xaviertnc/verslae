<?php

/*
 *
 * REGISTRASIE MODEL
 * By: C. Moller - 05 May 2016
 *
 */

Class RegistrasieModel
{
	public static $itemspp;
	public static $itemcount;
	public static $tailcount;
	public static $pagecount;
	public static $orderby;
	public static $ekspo_id;
	public static $columns = [];


	public static function csvInit($itemspp = 500)
	{
		self::$itemspp = $itemspp;
		self::$ekspo_id = Request::get('ekspo', __HUIDIGE_EKSPO_ID__);
		self::$itemcount = RegistrasiesRepo::kryAantalRegistrasies(self::$ekspo_id);
		self::$tailcount = self::$itemcount % $itemspp;
		self::$pagecount = floor(self::$itemcount / $itemspp);
		self::$orderby = Ui::$sort_widget->orderby();

		self::$columns[] = new ColumnEntity('#');

		$column_names = DB::columns('view_registrasies');
		foreach ($column_names as $column_name)
		{
			self::$columns[] = new ColumnEntity($column_name, ucfirst($column_name));
		}
	}


	public static function csvGetColumns($keep = null, $remove = null)
	{
		$columns = self::$columns;
		if ($keep) { $columns = array_filter($columns, function($v) use ($keep) { return in_array($v->name, $keep); }); }
		if ($remove) { $columns = array_filter($columns, function($v) use ($remove) { return ! in_array($v->name, $remove); }); }
		//Log::debug('RegistrasieModel::csvGetColumns(), ' . print_r($columns, true));
		return $columns;
	}


	public static function csvGetPageData($limit)
	{
		//Log::debug('RegistrasieModel::csvGetPageData(), limit = ' . $limit);
		return RegistrasiesRepo::lysRegistrasies(self::$ekspo_id, $limit, self::$orderby);
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

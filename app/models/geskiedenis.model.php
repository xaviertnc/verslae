<?php

/*
 *
 * GESKIEDENIS MODEL
 * By: C. Moller - 10 May 2016
 *
 */

Class GeskiedenisModel
{
	public static $itemspp;
	public static $itemcount;
	public static $tailcount;
	public static $pagecount;
	public static $orderby;
	public static $columns = [];


	public static function csvInit($itemspp = 1000)
	{
		self::$itemspp = $itemspp;
		self::$itemcount = DB::count('tblgeskiedenis');
		self::$tailcount = self::$itemcount % $itemspp;
		self::$pagecount = floor(self::$itemcount / $itemspp);
		self::$orderby = Ui::$sort_widget->orderby();

		self::$columns[] = new ColumnEntity('#');

		$column_names = DB::columns('view_geskiedenis');
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
		//Log::debug('GeskiedenisModel::csvGetColumns(), ' . print_r($columns, true));
		return $columns;
	}


	public static function csvGetPageData($limit = '')
	{
		//Log::debug('GeskiedenisModel::csvGetPageData(), limit = ' . $limit);
		$orderby = self::$orderby ? ' ORDER BY ' . self::$orderby : '';
		if ($limit) $limit = ' LIMIT ' . $limit;
		$inskrywings = DB::select('view_geskiedenis' . $orderby . $limit);
		return $inskrywings;
	}


	public static function lysGebruikers()
	{
		//Log::debug('GeskiedenisModel::lysGebruikers()');
		$gebruikers = DB::select('tblgebruikers');
		return $gebruikers;
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

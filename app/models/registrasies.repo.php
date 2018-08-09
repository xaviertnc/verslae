<?php

/*
 *
 * REGISTRASIES REPO
 * By: C. Moller - 27 Apr 2016
 *
 */

class RegistrasiesRepo
{

	public static function kryRegistrasies($ekspo_id, $limit = null, $orderby = null)
	{
		$sql = 'view_registrasies WHERE ekspo_id=?';
		if ($orderby) $sql .= ' ORDER BY ' . $orderby;
		if ($limit) $sql .= ' LIMIT ' . $limit;
		$registrasies = DB::select($sql, [$ekspo_id]);
		return $registrasies;
	}


	public static function kryAantalRegistrasies($ekspo_id)
	{
		return DB::count('tblregistrasies', 'WHERE `ekspo_id`=?', [$ekspo_id]);
	}


	public static function kryRegistrasiesOpsomming($ekspo_id)
	{
		$opsomming = DB::first('view_registrasiesopsomming', 'WHERE ekspo_id=?', [$ekspo_id]);
		if ( ! $opsomming)
		{
			$opsomming = new stdClass;
			$columns = DB::columns('view_registrasiesopsomming');
			foreach ($columns as $column_name) $opsomming->$column_name = 0;
		}
		return $opsomming;
	}


	public static function krySolidariteitRegistrasies($ekspo_id, $limit = null, $orderby = null)
	{
		$sql = 'view_registrasies WHERE ekspo_id=? AND (solidariteitlid=1 OR solidariteitkontak=1)';
		if ($orderby) $sql .= ' ORDER BY ' . $orderby;
		if ($limit) $sql .= ' LIMIT ' . $limit;
		$registrasies = DB::select($sql, [$ekspo_id]);
		return $registrasies;
	}


	public static function kryTotaalSolidariteit($ekspo_id)
	{
		return DB::count('tblregistrasies', 'WHERE `ekspo_id`=? AND (solidariteitlid=1 OR solidariteitkontak=1)', [$ekspo_id]);
	}


	public static function krySolidariteitOpsomming($ekspo_id)
	{
		$opsomming = DB::first('view_registrasiesopsomming', 'WHERE ekspo_id=?', [$ekspo_id]);
		return $opsomming;
	}

}

<?php

/*
 *
 * REGISTRASIES REPO
 * By: C. Moller - 27 Apr 2016
 *
 * @update: C. Moller - 10 Aug 2018
 *  - Vervang Solidariteit veldname met "Borg"
 *  - Verbeter VIEW logika
 *  - Las kommentaar oor struktuur van resultate.
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


	public static function kryAantalBorgRegistrasies($ekspo_id)
	{
		return DB::count('tblregistrasies', 'WHERE `ekspo_id`=? AND (NOT reedsborglid OR borgkanmykontak=1)', [$ekspo_id]);
	}
  

	public static function lysBorgRegistrasies($ekspo_id, $limit = null, $orderby = null)
	{
		$sql = 'view_registrasies WHERE ekspo_id=? AND (NOT reedsborglid OR borgkanmykontak=1)';
		if ($orderby) $sql .= ' ORDER BY ' . $orderby;
		if ($limit) $sql .= ' LIMIT ' . $limit;
		$registrasies = DB::select($sql, [$ekspo_id]);
		return $registrasies;
	}

  
  /*
   * @return Object $registrasiesopsomming
   *
   * Object $registrasiesopsomming:
   * ==============================
   * @field Integer ekspo_id
   * @field Integer totaal           Totale aantal registrasies vir die ekspo in die 'ekspo_id' kolom 
   * @field Integer vooraf           Aantal vooraf-registrasies (tipes: 1 tot 6) vir die spesifieke ekspo 
   * @field Integer hek              Aantal hek-registrasies (tipes: 7 tot 12) vir die spesifieke ekspo 
   * @field Integer hek_betaal       Aantal hek-registrasies wat moes betaal (tipes: 8,9,11,12) vir ekspo 
   * @field Integer nognieborglidnie Aantal registrasies met 'reedsborglid` == NULL 
   * @field Integer borgkanmykontak  Aantal registrasies met 'borgkanmykontak` == 1 
   * @field Integer nie_lede_kontak  Aantal registrasies met 'borgkanmykontak` == 1 en 'reedsborglid' == NULL
   * @field Integer verwysings       Aantal registrasie-verwysings vir die spesifieke ekspo
   *
   */   
	public static function kryBorgOpsomming($ekspo_id)
	{
		$registrasiesopsomming = DB::first('view_registrasiesopsomming', 'WHERE ekspo_id=?', [$ekspo_id]);
		return $registrasiesopsomming;
	}

  
  /*
   * @return Object $regtipesopsomming
   *
   * Object $regtipesopsomming:
   * ==============================
   * @field Integer ekspo_id
   * @field Integer totaal        Totale aantal registrasies vir die ekspo in die 'ekspo_id' kolom 
   * @field Integer vooraf        Aantal vooraf-registrasies (tipes: 1 tot 6) vir die spesifieke ekspo 
   * @field Integer hek           Aantal hek-registrasies (tipes: 7 tot 12) vir die spesifieke ekspo 
   * @field Integer hek_betaal    Aantal hek-registrasies wat moes betaal (tipes: 8,9,11,12) vir ekspo 
   *
   */  
	public static function kryRegTipesOpsomming($ekspo_id)
	{
		$regtipesopsomming = DB::first('view_regtipesopsomming', 'WHERE ekspo_id=?', [$ekspo_id]);
		if ( ! $regtipesopsomming)
		{
			$regtipesopsomming = new stdClass;
			$columns = DB::columns('view_regtipesopsomming');
			foreach ($columns as $column_name) { $regtipesopsomming->$column_name = 0; }
		}
		return $regtipesopsomming;
	}

}

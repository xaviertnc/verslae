<?php

/*
 *
 * ONTVANGS REPO
 * By: C. Moller - 09 May 2016
 *
 * @update: C. Moller - 10 Aug 2018
 *
 */

class OntvangsRepo
{
  /*
   * @return Object $opgedaagOpsomming
   *
   * Object $opgedaagOpsomming:
   * ==============================
   * @field Integer ekspo_id
   * @field Integer registrasies_totaal   Totale aantal registrasies vir die ekspo in die 'ekspo_id' kolom 
   * @field Integer volwassenes_totaal    Aantal vooraf-registrasies (tipes: 1 tot 6) vir die spesifieke ekspo 
   * @field Integer volwassenes_dag1      Aantal hek-registrasies (tipes: 7 tot 12) vir die spesifieke ekspo 
   * @field Integer volwassenes_dag2      Aantal hek-registrasies wat moes betaal (tipes: 8,9,11,12) vir ekspo 
   * @field Integer volwassenes_dag3      Aantal registrasies met 'reedsborglid` == NULL 
   * @field Integer kinders_totaal        Aantal registrasies met 'borgkanmykontak` == 1 
   * @field Integer kinders_dag1          Aantal registrasies met 'borgkanmykontak` == 1 en 'reedsborglid' == NULL
   * @field Integer kinders_dag2          Aantal registrasie-verwysings vir die spesifieke ekspo
   * @field Integer kinders_dag3          Aantal registrasie-verwysings vir die spesifieke ekspo
   *
   */ 
	public static function kryOpgedaagOpsomming($ekspo_id)
	{
		$opgedaagOpsomming = DB::first('view_opgedaagopsomming', 'WHERE ekspo_id=?', [$ekspo_id]);
		if ( ! $opgedaagOpsomming)
		{
			$opgedaagOpsomming = new stdClass;
			$columns = DB::columns('view_opgedaagopsomming');
			foreach ($columns as $column_name) $opgedaagOpsomming->$column_name = 0;
		}
		return $opgedaagOpsomming;
	}


	public static function kryOpgedaagUniekOpsomming($ekspo_id)
	{
		$resultaat = DB::first('view_uniekopgedaagopsomming', 'WHERE ekspo_id=?', [$ekspo_id]);
		if ( ! $resultaat)
		{
			$resultaat = new stdClass;
			$columns = DB::columns('view_uniekopgedaagopsomming');
			foreach ($columns as $column_name) $resultaat->$column_name = 0;
		}
		return $resultaat;
	}


	public static function lysOpdagings($ekspo_id)
	{
		$resultaat = DB::select('view_opgedaag WHERE ekspo_id=?', [$ekspo_id]);
		return $resultaat ?: [];
	}

  
  /* Lys net EEN opdaging per registrasie per dag */
  /* Raporteer die grootste volwasses- en kinderswaarde gevind in al die ekspodae se opdagings! */
	public static function lysOpdagingsUniek($ekspo_id)
	{
		$resultaat = DB::select('view_uniekopgedaag WHERE ekspo_id=?', [$ekspo_id]);
		return $resultaat ?: [];
	}
  
  
	public static function verwyderOpdaging($opdaging_id)
	{
		return DB::deleteFrom('tblopdagings', 'WHERE id=?', [$opdaging_id]);
	}  

}

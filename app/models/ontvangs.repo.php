<?php

/*
 *
 * ONTVANGS REPO
 * By: C. Moller - 09 May 2016
 *
 */

class OntvangsRepo
{

	public static function kryOntvangsOpsomming($ekspo_id)
	{
		$opsomming = DB::first('view_ontvangsopsomming', 'WHERE ekspo_id=?', [$ekspo_id]);
		if ( ! $opsomming)
		{
			$opsomming = new stdClass;
			$columns = DB::columns('view_ontvangsopsomming');
			foreach ($columns as $column_name) $opsomming->$column_name = 0;
		}
		return $opsomming;
	}


	public static function kryOpdagingsPerBesoeker($ekspo_id, $ekspo_dag)
	{
		$subsql  = 'select `os`.`registrasie_id` AS `registrasie_id`, count(0) AS `opdagings` ';
		$subsql .= 'from `tblopgedaag` AS `os` left join `tblregistrasies` AS `rs` on `rs`.`id` = `os`.`registrasie_id` ';
		$subsql .= 'where (`rs`.`ekspo_id` = ? and `os`.`dag` < ?) group by `os`.`registrasie_id`';

		$sql  = 'select `r`.`ekspo_id` AS `ekspo_id`, `r`.`registrasietipe_id` AS `registrasietipe_id`, `o`.`dag` AS `dag`, ';
		$sql .= 'count(0) as `volwasses`, sum(if(`x`.`opdagings`,0,1)) AS `volwasses_uniek`,sum(`o`.`kinders`) AS `kinders` ';
		$sql .= 'from `tblopgedaag` as `o` left join `tblregistrasies` as `r` on `r`.`id` = `o`.`registrasie_id` ';
		$sql .= 'left join (' . $subsql . ') `x` on `x`.`registrasie_id`=`o`.`registrasie_id` ';
		$sql .= 'where (`r`.`ekspo_id` = ? and `o`.`dag` = ?) ';
		$sql .= 'group by `r`.`ekspo_id`, `r`.`registrasietipe_id`, `o`.`dag`';

		return DB::exec($sql, [$ekspo_id, $ekspo_dag, $ekspo_id, $ekspo_dag]);
	}


}

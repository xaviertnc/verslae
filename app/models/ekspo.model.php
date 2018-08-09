<?php

/*
 *
 * EKSPO MODEL
 * By: C. Moller - 28 Apr 2016
 *
 */

Class EkspoModel
{

	public static function kiesEeen($ekspos, $ekspo_id)
	{
		foreach ($ekspos as $ekspo)
		{
			if ($ekspo->id == $ekspo_id) return $ekspo;
		}
	}


	public static function kryAantalDae($ekspo)
	{
		$date1 = new DateTime($ekspo->begindatum);
		$date2 = new DateTime($ekspo->einddatum);
		return ($date2->diff($date1)->d + 1);
	}
}

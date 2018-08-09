<?php

/*
 *
 * DAGNAME REPO
 * By: C. Moller - 28 Apr 2016
 *
 */

Class DagNameRepo
{

	public static function kryDagName()
	{
		return DB::select('tbldagname');
	}

}

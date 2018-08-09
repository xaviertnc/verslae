<?php

/*
 *
 * EKSPOS REPO
 * By: C. Moller - 28 Apr 2016
 *
 */

Class EksposRepo
{

	public static function kryEkspos()
	{
		return DB::select('tblekspo');
	}

}

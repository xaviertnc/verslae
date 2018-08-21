<?php

/**
 * JOURNAL SERVICE
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 05 May 2016
 *
 */
class Journal
{

	//public static $entry_types = array(1=>'Inteken Suksesvol', 2=>'Inteken Onsuksesvol', 3=>'Toegang Verbode', 4=>'Nie Ingeteken', 5=>'Teken Uit', 6=>'CSV Afgelaai');


	public static function log($entry_type_id, $message)
	{
		$auth_user = Auth::getAuthUser();
		$auth_user_id = $auth_user ? $auth_user->id : 0;
		$data = array(
			'tipe_id' => $entry_type_id,
			'gebruiker_id' => $auth_user_id,
			'module_id' => 6,
			'beskrywing' => $message
		);

		DB::insertInto('tblgeskiedenis', $data);
    
	}

}

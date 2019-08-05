<?php

/**
 * App Authentication Service
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 26 Apr 2016
 *
 */
class Auth
{

	private static $user = null;


	public static function getAuthUser($username = null, $password = null)
	{
		if (self::$user) return self::$user;

		if ($username and $password)
		{
			$result = DB::first('tblgebruikers', 'WHERE gebruikernaam=? AND ou_wagwoord=?', [$username, $password]);

			if ($result)
			{
				self::$user = $result;
				State::setAuth('user', serialize($result));
			}
		}
		else
		{
			self::$user = unserialize(State::getAuth('user'));
		}

		return self::$user;
	}


	public static function getGuestUser()
	{
		$guest = new stdClass;
		$guest->naam = '';
		return $guest;
	}


	public static function check($allowed_access, $loginUrl = __LOGIN_URL__, $guestHomeUrl = __GUEST_HOME_URL__)
	{
		$user = self::getAuthUser();

		if (empty($user))
		{
			State::addFlash('warning', 'Teken asb. in');
			Journal::log(2, 'Ongeldige sessie: ' . Request::$uri);
			Redirect::to($loginUrl);
		}

		if ($user->toegang == 'super') return $user;

		// if (is_string($allowed_access)) $allowed_access = array($allowed_access);

		$allowed_access_types = explode(',', $allowed_access);
		$user_access_types = explode(',', $user->toegang);

		foreach	($allowed_access_types as $access_type)
		{
			if ( ! in_array($access_type, $user_access_types))
			{
				State::addFlash('danger', 'Toegang verbode');
				Journal::log(1, 'Toegang verbode! Blok: ' . Request::$uri);
				Auth::logout($guestHomeUrl);
				break;
			}
		}

		return $user;
	}


	public static function login($username, $password)
	{
		$user = self::getAuthUser($username, $password);
		if (empty($user)) {
			State::delAuth();
			State::addFlash('danger', 'Inteken onsuksesvol');
			Journal::log(1, 'Inteken poging onsuksesvol: ' . $username);
			Redirect::back();
		}

		State::delAlert(); // Clear all Alerts!
		Journal::log(1, 'Teken in op Verslae: ' . $username);
		//Redirect::to($user->tuisskakel ? __HOME_URL__ . $user->tuisskakel : __HOME_URL__);
		Redirect::to(__HOME_URL__);
	}


	public static function logout($guestHomeUrl = __GUEST_HOME_URL__)
	{
		State::delAuth();
		Redirect::to($guestHomeUrl);
		die();
	}

}

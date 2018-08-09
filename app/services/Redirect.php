<?php

//NOTE:  NO NAMESPACE!  I.e. This class is in global scope and static.

/**
 * App Specific Redirect Services Class
 *
 * This Class is NOT intentded as a lib!  It is customized for this APP.
 * If you want to create re-usable routing fucntions, make an extendable LIB class and access it through a FACADE.
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 20 Sep 2014
 *
 * @updated 16 Feb 2016
 *   - Removed "App" infront of static class names
 *   - Removed all routing related code
 *
 */
class Redirect
{

    public static function to($url)
	{
		redirect($url);

		die;
	}


	public static function back($defaultUrl = '/')
	{
		$back = History::referer($defaultUrl);

		Log::redirect('Redirect::back(), back_url: ' . $back);

		redirect($back);

		die;
	}

}

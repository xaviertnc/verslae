<?php namespace SmsBot;

use History as HistoryFacade;

// NOTE: We use namespacing to avoid a classname clash with the History Facade
// Not really needed here but we stick to the format for consistency.


/**
 * App Nav History
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 19 Feb 2016
 *
 */
class AppNavHistory extends \OneFile\History
{

	protected function _session_forget($key)
	{
		\Session::forget($key);
	}


	protected function _session_write($key, $value)
	{
		\Session::put($key, $value);
	}


	protected function _session_read($key, $default = null)
	{
		return \Session::get($key, $default);
	}

}


$app->history = new AppNavHistory();

HistoryFacade::setFacadeHost($app->history);

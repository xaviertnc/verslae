<?php namespace Services;

use State as StateFacade;

// NOTE: We use namespacing to avoid a classname clash with the State Facade
// Not really needed here but we stick to the format for consistency.


/**
 * App State Messages
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 19 Feb 2016
 *
 */
class AppStateMessages extends \OneFile\Messages
{

	public function __construct()
	{
		parent::__construct();
		$bag = \Session::get('__STATE__');
		if ($bag) { $this->bag = $bag; }
	}


	public function __destruct()
	{
		\Session::put('__STATE__', $this->bag);
	}

}


$app->status = new AppStateMessages();

StateFacade::setFacadeHost($app->status);

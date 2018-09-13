<?php namespace Services;

use Log as LoggerFacade;

// NOTE: We use namespacing to avoid a classname clashes.
// Not really needed here but we stick to the format for consistency.


/**
 * App Log
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 11 Sep 2014
 *
 * @updated 18 Feb 2016
 *    - Changed classnames and uses clauses
 *
 */

class Logger extends \OneFile\Logger
{

	public function __construct()
	{
		//$allowedTypes = 'error|warning|test';

		$allowedTypes = (__ENV__ == 'production' or ! __DEBUG__) ? 'error|warning' : null;

		parent::__construct(__LOGS__, $allowedTypes);

		$this->setFilename('tuisskool_' . $this->getDate() . '_' . substr(\Session::id(), 0, 5) . '.log');
	}

}


$app->logger = new Logger();

LoggerFacade::setFacadeHost($app->logger);

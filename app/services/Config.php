<?php namespace Services;

use Config as ConfigFacade;

// NOTE: We use namespacing to avoid a classname clash with the Config Facade


/**
 * Customize the OneFile/Config class for this App
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 16 Feb 2016
 *
 * @updated 18 Feb 2016
 *    - Changed classnames and uses clauses
 *
 */

class Config extends \OneFile\Config
{

	/**
	 * Our custom Config loader start-up.  Include environment specific configs.
	 */
	public function __construct()
	{
		parent::__construct(__CONFIG__ . '/app.php');
	}

}


$app->config = new Config();

ConfigFacade::setFacadeHost($app->config);

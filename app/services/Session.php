<?php namespace Services;

use Session as SessionFacade;

// NOTE: We use namespacing to avoid a classname clash with the Session Facade


/**
 * App Session
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 11 Sep 2014
 *
 * @updated 18 Feb 2016
 *    - Changed classnames and uses clauses
 *
 */
class Session extends \OneFile\Session
{
    public function __construct()
    {
        parent::__construct('kragdag', 'kdgvs');
    }
}


$app->session = new Session();

SessionFacade::setFacadeHost($app->session);

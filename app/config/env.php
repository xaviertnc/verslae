<?php

function detect_environment()
{
    //$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;

	// local: Use a local version of the production database (Will need to merge changes
	// to the actual actual production database later. (For off-site emergency transactions)
	//if ($host == 'localhost' or strpos($host, '.local' )) { return 'local'; }

	// development: Use a copy of the production database, but don't care about the data. No need
	// to merge changes later! Just replace with a newer copy from time to time.
	//if (strpos($host, '.dev' )) { return 'dev'; }

	// test: Database only contains test meters and an additional user to test the "front-end"
	//if (strpos($host, '.comp')) { return 'comp'; }

	// compliance: Database only contains compliance meters and an additional user to verify the "front-end"
	//if (strpos($host, '.test')) { return 'test'; }

	// Default to "production"
	return 'prod';
}


define('__VER__'        , '0.31');
define('__ENV__'        , detect_environment());

define('__CONFIG__'     , __APP__ . '/config');
define('__VENDORS__'    , __APP__ . '/vendor');
define('__SERVICES__'   , __APP__ . '/services');
define('__MODELS__'     , __APP__ . '/models');
define('__SNIPPETS__'   , __APP__ . '/snippets');
define('__WIDGETS__'    , __APP__ . '/widgets');
define('__ERRORS__'     , __APP__ . '/errors');

define('__ONEFILE__'    , __VENDORS__ . '/OneFile');

define('__PUBLIC_HTML__', dirname(__APP__));
define('__STORAGE__'    , __PUBLIC_HTML__ . '/storage');
define('__LOGS__'       , __STORAGE__ . '/logs');

define('__JQUERY__'         , 'js/jquery-2.2.0.min.js');
define('__BOOTSTRAP_JS__'   , 'js/bootstrap.min.js');
define('__SELECT_JS__'      , 'js/jquery.sumoselect.min.js');

define('__BOOTSTRAP_CSS__'  , 'css/bootstrap.min.css');
define('__SELECT_CSS__'     , 'css/jquery.sumoselect.css');
define('__CUSTOM_CSS__'     , 'css/style.css');

define('__TIMEZONE__'   , 'Africa/Johannesburg');

define('__ALLOW_CORS__' , false);

define('__APP_TITLE__'  , 'KragDag Verslae');

define('__HUIDIGE_EKSPO_ID__', 8);



// SETUP ENVIRONMENT SENSITIVE CONSTANTS

switch (__ENV__)
{
    case 'dev':
        define('__DEBUG__', true);
        define('__DEBUG_LEVEL__', 'TRACE');	// DETAIL, TRACE, EVENT, ERROR
        define('__WEBROOT__', '/kragdag/verslae/');	// Trailing '/' very important!  Only add for HOME!!!

		define('__LOGIN_URL__', __WEBROOT__ . 'tekenin/');
		define('__GUEST_HOME_URL__', __LOGIN_URL__);
		define('__HOME_URL__', __WEBROOT__);

        ini_set('log_errors', 0);			// We do our own debug logging
        ini_set('display_errors', 1);		// We do our own error handling

        error_reporting(E_ALL);
        //error_reporting(0);

        break;

    case 'prod':

    default:
        define('__DEBUG__', false);
        define('__DEBUG_LEVEL__', 'ERROR');			// DETAIL, TRACE, EVENT, ERROR
        define('__WEBROOT__', '/kragdag/verslae/');	// Trailing '/' very important!  Only add for HOME!!!

		define('__LOGIN_URL__', __WEBROOT__ . 'tekenin/');
		define('__GUEST_HOME_URL__', __LOGIN_URL__);
		define('__HOME_URL__', __WEBROOT__);

        ini_set('log_errors', 'On');				// We do our own debug logging
        ini_set('display_errors', 'Off');			// We do our own error handling

        error_reporting(0);
}

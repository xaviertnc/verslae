<?php

define('__APP_START__', microtime(true));


session_start();


// ON-SHUTDOWN and EARLY-BIRD ERROR HANDLER

register_shutdown_function(function() {
    if (error_get_last() !== NULL && ! class_exists('Errors')) {
		echo '<div style="color:red">Oops, Fatal Application Bootstrap Error!</div>' . PHP_EOL;
        if (defined('__DEBUG__') and __DEBUG__ and function_exists('dd')) {
			dd(error_get_last());
		} else {
			die(print_r(error_get_last(), true));
		}
    }
});


// DETECT ENVIRONMENT AND SETUP RELATED GLOBALS

define('__APP__', __DIR__);
include dirname(__APP__) . '/env-local.php';
include __APP__. '/config/env.php';


// LOAD VENDOR LIBS

include __VENDORS__ . '/Xap/Engine.php';
if (__DEBUG__) { include __VENDORS__ . '/Kint/Kint.class.php'; function pretty($var, $level = 0) { return kintLite($var, $level); } }
else { function pretty($var, $level = 0) { return print_r($var, true) . ($level ? ", loglevel=$level" : ''); } }


// LOAD IN-HOUSE LIBS

include __ONEFILE__ . '/Logger.php';
include __ONEFILE__ . '/View.php';
include __ONEFILE__ . '/File.php';
include __ONEFILE__ . '/Folder.php';
include __ONEFILE__ . '/Format.php';
include __ONEFILE__ . '/Sanitize.php';
include __ONEFILE__ . '/Facade.php';
include __ONEFILE__ . '/Config.php';
include __ONEFILE__ . '/Session.php';
include __ONEFILE__ . '/History.php';
include __ONEFILE__ . '/Messages.php';
include __ONEFILE__ . '/Functions.php';


// ALIAS SOME NAMESPACED CLASSES FOR CONVENIENCE

class_alias('\OneFile\Facade'  , 'Facade');
class_alias('\OneFile\Format'  , 'Fmt');
class_alias('\OneFile\Sanitize', 'Sanitize');


// FACADE CORE NON-STATIC SERVICE CLASSES FOR CONVENIENCE

class Session    extends Facade {}
class History    extends Facade {}
class Config     extends Facade {}
class Scripts    extends Facade {}
class Styles     extends Facade {}
class State      extends Facade {}
class Log        extends Facade {}


// CREATE GLOBAL APP SERVICES CONTAINER

$app = new stdClass;


// START APP SERVICE IMPLEMENTATIONS

include __SERVICES__ . '/Session.php';
include __SERVICES__ . '/Config.php';
include __SERVICES__ . '/Logger.php';
include __SERVICES__ . '/History.php';
include __SERVICES__ . '/Request.php';
include __SERVICES__ . '/Redirect.php';
include __SERVICES__ . '/Errors.php';
include __SERVICES__ . '/Journal.php';
include __SERVICES__ . '/Scripts.php';
include __SERVICES__ . '/Styles.php';
include __SERVICES__ . '/State.php';
include __SERVICES__ . '/Debug.php';
include __SERVICES__ . '/Auth.php';
include __SERVICES__ . '/Db.php';
include __SERVICES__ . '/Ui.php';

Debug::logBootInfo();

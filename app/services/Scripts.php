<?php namespace Services;

use Scripts as ScriptsFacade;

// NOTE: We use namespacing to avoid a classname clash with the Scripts Facade
// Not really needed here but we stick to the format for consistency.


/**
 * App Scripts - A service to centrally manage JS scripts to execute.
 *
 * This is different from the "scripts widget" which only handles the rendering / appending
 * of the required scripts collection to the HTML document.
 *
 * We need a global Scripts service for most widgets to have a way add their required JS code.
 * A Scripts service is much cleaner than tieing other widgets directly to the "scripts widget"... Just my opinion ;-)
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 21 Feb 2016
 *
 */
class AppScripts extends \OneFile\Messages {}


$app->scripts = new AppScripts();

ScriptsFacade::setFacadeHost($app->scripts);

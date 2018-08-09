<?php namespace Services;

use Styles as StylesFacade;

// NOTE: We use namespacing to avoid a classname clash with the Styles Facade
// Not really needed here but we stick to the format for consistency.


/**
 * App Styles - A service to centrally manage JS styles to execute.
 *
 * This is different from the "styles widget" which only handles the rendering / appending
 * of the required styles collection to the HTML document.
 *
 * We need a global Styles service for most widgets to have a way add their required JS code.
 * A Styles service is much cleaner than tieing other widgets directly to the "styles widget"... Just my opinion ;-)
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 21 Feb 2016
 *
 */
class AppStyles extends \OneFile\Messages {}


$app->styles = new AppStyles();

StylesFacade::setFacadeHost($app->styles);

<?php //NOTE:  NO NAMESPACE!  I.e. This class is in global scope and static.

/**
 * App Specific Request Services Class
 *
 * This Class is NOT intentded as a "real" lib!  It is customized specifically for this APP.
 * If you want to create re-usable routing fucntions, make an extendable LIB class and access it through a FACADE.
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 06 Sep 2014
 *
 * @updated 16 Feb 2016
 *   - Change AppRequest to just Request.  Removed $route static.
 *   - merged Request::$params into Request::$data
 *
 */
class Request
{

    public static $uri;
    public static $data;
    public static $headers;
    public static $action;
    public static $method;
    public static $origin;
    public static $type;
    public static $ucAction;
    public static $authorization;


    public static function get($paramName, $default = null)
    {
        return array_get(Request::$data, $paramName, $default);
    }


    public static function ajax()
    {
        return self::$type == 'xmlhttprequest';
    }


    public static function getUpdatedUrlParams(array $update, $keep = null, $remove = null)
    {
		$params = [];
		if (empty($keep)) { $keep = []; }
		if (empty($remove)) { $remove = []; }

		//Log::debug('Request::getUpdatedUrlParams(), $_REQUEST = ' . pretty($_REQUEST));
		//Log::debug('Request::getUpdatedUrlParams(), $update = ' . pretty($update));
		//Log::debug('Request::getUpdatedUrlParams(), $keep = ' . pretty($keep));
		//Log::debug('Request::getUpdatedUrlParams(), $remove = ' . pretty($remove));

		foreach ($_REQUEST?:[] as $key=>$value)
		{
			//Log::debug('Request::getUpdatedUrlParams(), Passed REMOVE, key = ' . $key . ', value = ' . $value);

			if ( ! empty($keep) and ! in_array($key, $keep)) continue;

			//Log::debug('Request::getUpdatedUrlParams(), Passed KEEP');

			if ( ! empty($remove) and in_array($key, $remove)) continue;

			//Log::debug('Request::getUpdatedUrlParams(), Passed REMOVE');

			if (isset($update[$key]))
			{
				$params[$key] = $update[$key];
				unset($update[$key]);
			}
			else
			{
				$params[$key] = $value;
			}
		}

		// Add any remaining "$update" params like when we have no $_REQUEST yet,
		// but want to ADD request params like "p" and "ipp" for links.
		// The $_REQUEST loop above will remove any already updated "$update" entries
		foreach ($update as $key=>$value)
		{
			if ($keep and ! in_array($key, $keep)) continue;
			if (in_array($key, $remove)) continue;
			$params[$key] = $update[$key];
		}

		//Log::debug('Request::getUpdatedUrlParams(), params = ' . pretty($params));
		//Log::debug('');

		return $params;
    }


    public static function getUpdatedQueryStr(array $update, $keep = null, $remove = null)
    {
		$http_query = http_build_query(self::getUpdatedUrlParams($update, $keep, $remove));
		return $http_query ? '?' . $http_query : '';
	}

}


Request::$data = $_REQUEST;
Request::$action = array_get(Request::$data, 'do', 'index');
Request::$ucAction = ucfirst(Request::$action);
Request::$method = strtoupper(array_get($_SERVER, 'REQUEST_METHOD', 'Unknown'));
Request::$origin = array_get($_SERVER, 'HTTP_REFERER', 'Unknown') . ' - ' . array_get($_SERVER, 'REMOTE_ADDR', 'No IP');
Request::$type = strtolower(array_get($_SERVER, 'HTTP_X_REQUESTED_WITH'));
Request::$uri = array_get($_SERVER, 'REQUEST_URI', 'Not Set!');
Request::$headers = getallheaders();
Request::$authorization = array_get(Request::$headers, 'Authorization');

if (Request::$method == "GET")
{
	History::update(Request::$uri);
}

// For interfacing with Angular HTTP that sends data in JSON format
if (Request::$method == 'POST' && empty($_POST))
{
    Request::$data = json_decode(file_get_contents('php://input'), true);
}

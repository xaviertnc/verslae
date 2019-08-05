<?php

//NOTE:  NO NAMESPACE!  I.e. This class is in global scope and static.


// REGISTER CUSTOM PHP DEFAULT ERROR HANDLERS

set_error_handler("Errors::handleError");

set_exception_handler("Errors::handleException");

register_shutdown_function("Errors::handleFatal");


/**
 * Custom Application Error Handler
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 09 Sep 2014
 *
 * @updated 16 Feb 2016
 *
 */
class Errors
{

	public static function raise($errorText = null, $errorHtml = null, $code = 500, $logtype = 'error')
	{
		if (! $errorHtml) $errorHtml = $errorText;

		Log::$logtype($errorText);

		if (! __DEBUG__) die; //NB: Must be AFTER "Log" above!

		if (__ENV_PROD__)
		{
			if (ob_get_level()) ob_end_clean();

			http_response_code($code);

			if (Request::ajax())
			{
				header('Cache-Control: no-cache, must-revalidate');
				header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
				header('Content-type: application/html');
				die($errorHtml
				    ? "<div style=\"background-color:white\"><span style=\"display:inline-block; padding:3px 7px; margin-bottom:4px; border:1px solid firebrick; background-color:yellow;\">Env: PROD + DEBUG!</span><br/>$errorHtml</div>"
					: "HTTP Error $code"
				);
			}
			else
			{
				include __ERRORS__ . "/$code.php";
				die;
			}
		}

		// Ajax or NOT, we return a nicely formatted HTML error. I.e. $error should be HTML.
		http_response_code($code);

		die($errorHtml);
	}


	public static function makeNiceTrace($stacktrace, $asHtml = true, $print = true)
	{
        $nl = $asHtml ? '<br>' : "\n";

		$niceTrace = str_repeat('=', 50) . $nl;

        $i = 1;
		foreach ($stacktrace as $node)
		{
            $niceTrace .= "$i. " . basename(array_get($node, 'file', 'no-file')) . ':';
			$niceTrace .= array_get($node, 'function', 'unknown-function');
			$niceTrace .= '(' . array_get($node, 'line', '?') . ')' . $nl;
            $i++;
        }

		if ($print) print($niceTrace);

		return $niceTrace;
    }


	public static function handleError($errno, $errstr, $errfile, $errline, $fatal = null)
	{
		$textError = 'Oops, ' . ($fatal ? '*!Fatal!* ' : '') .
			"Error $errno: $errstr in file: $errfile (Line $errline)";

		$htmlError = '<div style="background-color:white; color:black; padding: 7px 12px;">'
		. '<div style="color:blue">Oops, Something Went Wrong!</div>'
		. '<div style="color:crimson">' . ($fatal ? '<b>Fatal</b> ' : '') . "Error $errno - $errstr in</div>"
		. "<div style=\"color:blue\">File: $errfile (Line $errline)</div>";

		$htmlError .= Errors::makeNiceTrace(debug_backtrace(), true, false);
		$htmlError .= '</div>';

		Errors::raise($textError, $htmlError);
	}


	public static function handleException(Exception $e)
	{
		$textError = "Exception {$e->getCode()}: {$e->getMessage()} in file: {$e->getFile()} (Line {$e->getLine()})";

		$htmlError = '<div style="background-color:white; color:black; padding: 7px 12px;">'
		. "<div style='color:blue'>Exception Code: {$e->getCode()}</div>"
		. "<div style='color:crimson'>{$e->getMessage()} in</div>"
		. "<div style='color:blue'>File: {$e->getFile()} (Line {$e->getLine()})</div>";

		$htmlError .= Errors::makeNiceTrace($e->getTrace(), true, false);
		$htmlError .= '</div>';

		Errors::raise($textError, $htmlError);
	}


	public static function handleFatal()
	{
		$error = error_get_last();

		if ($error !== NULL)
		{
			$errno   = array_get($error, 'type', E_CORE_ERROR);
			$errfile = array_get($error, 'file', 'unknown file');
			$errline = array_get($error, 'line', '?');
			$errstr  = array_get($error, 'message', 'shutdown');

			Errors::handleError($errno, $errstr, $errfile, $errline, 'FATAL');
		}

		Log::shutdown("Request Done. BYE\n\n");
	}

}


error_reporting(0); //Off since we display them ourselves from now on!


//***** DEFINE ALL CUSTOM EXCEPTION CLASSES HERE *****

//class MyCustomException extends \Exception {}

<?php

//NOTE:  NO NAMESPACE!  I.e. This class is in global scope and static.

/**
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 26 Jan 2015
 *
 * @updated 18 Feb 2016
 *   - Converted class to global static
 */
class Debug
{

    public static function logBootInfo()
    {
        //LOG APP START-UP STATE

        Log::boot(str_repeat('=', 60));

        Log::boot('APPLICATION START - '	. date('d F Y H:i:s', __APP_START__));
        Log::boot('REQUEST URI: '			. Request::$uri);
        Log::boot('REQUEST ACTION: '		. (Request::$action ? : '[none]'));
        Log::boot('REQUEST METHOD: '		. Request::$method);
        Log::boot('REQUEST AJAX: '			. Fmt::yesNo(Request::ajax()));
        Log::boot('REQUEST AUTH: '			. (Request::$authorization ? : '[none]'));
        Log::boot('ENVIRONMENT: '			. __ENV__);
        Log::boot(str_repeat('=', 60) . "\n");
        Log::boot('$_REQUEST = '			. pretty($_REQUEST));
        //Log::boot('$_SESSION = '			. pretty($_SESSION));

    }

}

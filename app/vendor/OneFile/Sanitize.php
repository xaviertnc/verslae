<?php namespace OneFile;

/**
 * Sanitize Class
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 27 Feb 2016
 *
 * Licensed under the MIT license. Please see LICENSE for more information.
 *
 */
class Sanitize
{

	public static function number($value)
	{
		return ($value != '') ? preg_replace('/[^0-9\-]/', '', $value) : null;
	}


	public static function decimal($value)
	{
		return ($value != '') ? preg_replace('/[^0-9\.\-]/', '', $value) : null;
	}


	public static function mysqlDate($value)
	{
		$parts = explode('-', str_replace('/', '-', preg_replace('/[^0-9\-]/', '', $value)));

		if(count($parts) == 3)
		{
			if(strlen($parts[0]) == 4)
			{
				$cen = $parts[0];
				$month = $parts[1];
				$day = $parts[2];
			}
			else
			{
				$cen = $parts[2];
				$month = $parts[1];
				$day = $parts[0];
			}

			if($cen >= 1800)
			{
				return $cen . '-' . $month . '-' . $day;
			}
			else
			{
				return;
			}
		}
		else
		{
			$time = strtotime($value);

			if($time === false)
			{
				return;
			}
			else
			{
				return date('Y-m-d', $time);
			}
		}
	}


	public static function mysqlTime($value)
	{
		$parts = explode(':', preg_replace('/[^0-9\:]/', '', $value));

		switch(count($parts))
		{
			case 1:
				$hour = $parts[0];
				$min = '';
				$sec = '';
				break;

			case 2:
				$hour = $parts[0];
				$min = ':' . $parts[1];
				$sec = '';
				break;

			case 3:
				$hour = $parts[0];
				$min = ':' . $parts[1];
				$sec = ':' . $parts[2];
				break;

			default:
				$hour = '';
		}

		if($hour)
		{
			if(isset($show_seconds))
			{
				return $hour . $min . $sec;
			}

			return $hour . $min;
		}
	}


	public static function alpha($value)
	{
		return ($value != '') ? preg_replace('/[^a-z\ ]/i', '', $value) : null;
	}


	public static function alphaNumeric($value)
	{
		return ($value != '') ? preg_replace('/[^a-z0-9]/i', '', $value) : null;
	}

}

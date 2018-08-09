<?php

/*
 *
 * OBJECT VIEW WIDGET CLASS
 * By: C. Moller - 29 Apr 2016
 *
 */

class ObjectViewWidget
{

	/*
	 *
	 *
	 */
	public function render($object, $remove = null)
	{
		$html = '';

		if ( ! $remove) { $remove = []; }

		if (is_object($object))
		{
			$indent = 1;
			$html .= indent($indent) . '<ul class="object-view">' . PHP_EOL;

			$indent++;
			foreach ($object as $key=>$value)
			{
				if (in_array($key, $remove)) continue;
				$html .= indent($indent) . '<li class="'.$key.'_row"><label>' . $key . '</label> = <span>' . $value . '</span></li>' . PHP_EOL;
			}

			$indent--;
			$html .= indent($indent) . '</ul>';
		}

		return trim($html) . PHP_EOL;
	}


	public function __toString()
	{
		return ' * ObjectView Widget * ';
	}

}

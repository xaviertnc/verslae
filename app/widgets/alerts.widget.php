<?php

/*
 *
 * ALERTS WIDGET CLASS
 * By: C. Moller - 20 Feb 2016
 *
 */

class AlertsWidget {

	public $alerts;
	public $types;


	public function __construct()
	{
		$this->alerts = State::getAlert('all');
		$this->types = array_keys($this->alerts);
	}


	public function dismiss_all($alert_reference)
	{
		State::delAlert();
	}

	public function dismiss_alert($alert_reference)
	{
		$reference_parts = explode('|', $alert_reference);

		$alert_group_name = $reference_parts[0];
		$alert_group_index = $reference_parts[1];

		$alert_group = State::getAlert($alert_group_name);

		if (is_array($alert_group))
		{
			unset($alert_group[$alert_group_index]);

			if ($alert_group)
			{
				State::setAlert($alert_group_name, $alert_group);
				return true;
			}
		}

		State::delAlert($alert_group_name);

		// If the Alert(s) array is empty, remove it.
		if (! State::hasAlert()) { State::delAlert(); }
		return true;
	}


	public function typegroup($type)
	{
		return is_array($this->alerts[$type]) ? $this->alerts[$type] : array($this->alerts[$type]);
	}


	protected function render($alerts)
	{
		// ALERTS WIDGET TEMPLATE
		$html = '<section id="alerts">' . PHP_EOL;

		$indent = 1;
		foreach ($this->types as $type)
		{
			foreach ($this->typegroup($type) as $index=>$alert)
			{
				$html .= indent($indent) . '<form method="post" id="alert_'.$type.'_'.$index.'" class="alert '.$type.'">' . PHP_EOL;

				$indent++;
				$html .= indent($indent) . '<button class="close-x '.$type.'" name="dismiss-alert" value="'.$type.'|'.$index.'" type="submit">X</button>' . PHP_EOL;
				$html .= indent($indent) . $alert . '<br>' . PHP_EOL;

				$indent--;
				$html .= indent($indent) . '</form>' . PHP_EOL;
			}
		}

		$html .= indent($indent) . '</section>' . PHP_EOL;
		return $html;
	}


	public function __toString()
	{
		return $this->render($this->alerts);
	}

}

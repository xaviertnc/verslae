<?php

include __WIDGETS__ . '/styles.widget.php';
include __WIDGETS__ . '/scripts.widget.php';
include __WIDGETS__ . '/alerts.widget.php';
include __WIDGETS__ . '/flash.widget.php';
include __WIDGETS__ . '/popup.widget.php';
include __WIDGETS__ . '/pager.widget.php';
include __WIDGETS__ . '/sort.widget.php';

/*
 *
 * USER INTERFACE (Ui) SERVICE
 * By: C. Moller - 28 Feb 2016
 *
 * Ui Manages all our frequently used widgets so we don't have to re-declare them on each page!
 *
 */

Class Ui
{
	public static $local_scripts_widget;
	public static $global_scripts_widget;
	public static $local_styles_widget;
	public static $global_styles_widget;
	public static $alerts_widget;
	public static $flash_widget;
	public static $popup_widget;
	public static $pager_widget;
	public static $sort_widget;

	public static $vendor_styles = ['select', 'custom'];
	public static $vendor_scripts = ['jquery', 'select'];
	public static $itemspp = 7;

	public static $base_url;


	public static function init($options = null)
	{
		//if (empty($options)) return;

		if (is_string($options)) { self::$base_url = $options; return; } else { self::$base_url = array_get($options, 'base-url', ''); }
		if (self::$base_url) { self::$base_url = trim(self::$base_url, '/') . '/'; }

		self::$itemspp = array_get($options, 'itemspp', self::$itemspp);
		self::$vendor_styles = array_get($options, 'vendor-styles', self::$vendor_styles);
		self::$vendor_scripts = array_get($options, 'vendor-scripts', self::$vendor_scripts);
		self::$global_scripts_widget = new ScriptsWidget('GlobalScripts', self::$vendor_scripts);
		self::$global_styles_widget = new StylesWidget('GlobalStyles', self::$vendor_styles);
		self::$local_scripts_widget = new ScriptsWidget('LocalScripts', [], 1);
		self::$local_styles_widget = new StylesWidget('LocalStyles', [], 1);
		self::$pager_widget = new PagerWidget();
		self::$popup_widget = new PopupWidget();
		self::$flash_widget = new FlashWidget();
		self::$alerts_widget = new AlertsWidget();
		self::$sort_widget = new SortWidget();

		Scripts::addLocalScripts('Ajax.bindLinks();');
	}


	public static function add_popup($options)
	{
		self::$popup_widget->add($options);
	}


	public static function handle_logout()
	{
		$logout = Request::get('logout');
		if ($logout)
		{
			Journal::log(1, 'Teken uit');
			Auth::logout();
			return true;
		}
	}


	public static function handle_alerts()
	{
		$dismiss_reference = Request::get('dismiss-alert');
		if ($dismiss_reference)
		{
			self::$alerts_widget->dismiss_alert($dismiss_reference);
			return true;
		}
	}


	public static function handle_popups($popup_name, $keep_params)
	{
		self::$popup_widget->config(self::$base_url, $keep_params);

		switch ($popup_name)
		{
			//case 'besoeker|pageno': //Combo example..

			case 'pageno':
				Ui::add_popup([
					'type'    => 'input',
					'title'   => '<b>Bladsynommer:</b>',
					'width'   => '300px',
					'buttons' => ['submit' => ['text'=>'Gaan na'], 'cancel' => 'Kanselleer'],
					'value'   => Request::get('p', 1)
				]);
		}
	}


	public static function userinfo()
	{
		if ($gebruiker = Auth::getAuthUser())
		{
			ob_start();
			?><li><i class="user-icon"></i>&nbsp;<?=$gebruiker->naam?></li>
			<li><form method="post"><button class="btn btn-logout" name="logout" type="submit" value="1">Teken Uit</button></form></li><?php
			echo PHP_EOL; return ob_get_clean();
		}
	}


	public static function supernav($ekspo_id)
	{
		if ($gebruiker = Auth::getAuthUser() and $gebruiker->toegang == 'super') { ob_start();
	?><nav class="row navbar navbar-primary">
		<ul class="col2 min350 nav">
			<li><a href="?ekspo=<?=$ekspo_id?>">Kragdag Oorsig</a> | </li>
			<li><a href="kragdag/registrasies?ekspo=<?=$ekspo_id?>">Registrasiesverslag |</a></li>
			<li><a href="borgverslag?ekspo=<?=$ekspo_id?>">Borgverslag</a></li>
		</ul>
		<div class="col1 min200 right">
			<ul class="nav">
				<li><!--Profile--></li>
			</ul>
		</div>
	</nav><?php	echo PHP_EOL; return ob_get_clean(); }
	}


	public static function footer()
	{
		$html = '&copy; KragDag ' . date('Y');
		if ($user = Auth::getAuthUser() and $user->toegang == 'super')
		{
			$html .= ', app-mode=' . __APP_MODE__ . ', ver=' . __VER__ . ', db=' . Config::get('database.connections.mysql.DBNAME');
		}
		else
		{
			$html .= ' (' . __APP_MODE__ . ' v' . __VER__ . ')';
		}

		return $html;
	}

}

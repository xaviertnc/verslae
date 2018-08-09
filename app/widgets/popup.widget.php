<?php

/*
 *
 * POPUP WIDGET CLASS
 * By: C. Moller - 21 Feb 2016
 *
 * Manages and render popups.
 *
 */

class PopupWidget {

	public static $next_id = 0;

	public $baseUrl;

	public $keep;
	public $remove;


	public function config($baseUrl, $keepParams = null, $removeParams = null)
	{
		$this->baseUrl = $baseUrl;
		$this->keep = empty($keepParams)?[]:$keepParams;
		$this->remove = empty($removeParams)?[]:$removeParams;
		return $this;
	}


	/*
	 *
	 * Serialize new popup options and store in State to display on redirect.
	 * Assign an unique id to each popup created
	 *
	 */
	public function add($options)
	{
		$popup = new stdClass();

		$popup->id = 'popup-' . self::$next_id++;


		if (is_array($options))
		{
			$popup->type    = array_get($options, 'type', 'info');
			$popup->width   = array_get($options, 'width', 'auto');
			$popup->title   = array_get($options, 'title');
			$popup->message = array_get($options, 'message');
			$popup->buttons = array_get($options, 'buttons', ['ok' => 'Ok']);
			$popup->value   = array_get($options, 'value');
		}
		else
		{
			$popup->type = 'info';
			$popup->width = 'auto';
			$popup->title = 'Information';
			$popup->message = $options;
			$popup->buttons = ['ok' => 'Ok'];
		}

		State::setPopup($popup->id, serialize($popup));
	}


	public function url($type = 'cancel')
	{
		switch ($type)
		{
			case 'cancel':
			default:
				return $this->baseUrl . '?' . http_build_query(Request::getUpdatedUrlParams([], $this->keep, ['dlg']));
		}
	}


	/*
	 *
	 * $popup ===  Popup options from State
	 *
	 * We trigger rendering by checking for any popups in the State
	 * or we can check for a switch in the query string and render explicitly.
	 *
	 */
	protected function render($popup)
	{
		switch ($popup->type)
		{
			case 'info': State::delPopup($popup->id); ob_start();

	?><section class="popup-backdrop hidden" id="<?=$popup->id?>">
		<div class="popup-window" style="width:<?=$popup->width?>">
			<h1><?=$popup->title?></h1>
			<p><?=$popup->message?></p>
			<div class="popup-buttons">
				<a class="btn btn-primary" href="<?=Request::$uri?>"><?=array_get($popup->buttons, 'ok', 'Ok')?></a>
			</div>
		</div>
	</section><?php echo PHP_EOL; return ob_get_clean();

			case 'input':
				State::delPopup($popup->id);
				$btnSubmit = array_get($popup->buttons, 'submit', []);
				if (is_string($btnSubmit)) { $btnSubmit = ['text' => $btnSubmit]; }
				Scripts::addLocalScriptsLate('$("#'.$popup->id.' input").select();');
				ob_start();

	?><section class="popup-backdrop hidden" id="<?=$popup->id?>">
		<div class="popup-window" style="width:<?=$popup->width?>"><?php echo PHP_EOL; if ($popup->title):?>
			<span class="popup-title"><?=$popup->title?></span><?php endif;	echo PHP_EOL; ?>
			<p><?php
				echo PHP_EOL; if ($popup->message):?><label class="popup-message"><?=$popup->message?></label><?php endif;?>
				<input name="value" value="<?=$popup->value?>" type="text" autocomplete="off" onkeydown="return Pager.onPageNoEnter(event,'<?=$popup->id?>')">
			</p>
			<div class="popup-buttons">
				<span class="btn btn-primary" onclick="return Pager.load('<?=$popup->id?>')"><?=array_get($btnSubmit, 'text', 'Submit')?></span>
				&nbsp;&nbsp;
				<a class="btn red" href="<?=$this->url('cancel')?>"><?=array_get($popup->buttons, 'cancel', 'Cancel')?></a>
			</div>
		</div>
	</section><?php echo PHP_EOL; return ob_get_clean();

		}

	}


	/*
	 *
	 * Check State to detemine what to render
	 *
	 * Somewhat different from Flash and Alert in that NOTHING gets rendered
	 * if no popups have been added.
	 *
	 * Alerts and Flash always renders the outer HTML!
	 *
	 */
	public function __toString()
	{
		$html = '';

		$popups = State::getPopup('all');

		if (empty($popups))
		{
			State::delPopup();
		}
		else
		{
			foreach ($popups as $popup)
			{
				$html .= $this->render(unserialize($popup));
			}
		}

		return $html;
	}

}

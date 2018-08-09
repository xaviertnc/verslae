<?php

/*
 *
 * STYLES WIDGET CLASS
 * By: C. Moller - 23 Apr 2016
 *
 */

class StylesWidget {

	protected $type;
	protected $uses;


	/*
	 *
	 * @param string $type values: GlobalStyles, LocalStyles
	 * @param string|array $uses Names of vendor libs to include
	 *
	 */
	public function __construct($type = 'GlobalStyles', $uses = null)
	{
		$this->type = $type;
		if (! is_array($uses)) { $uses = array($uses); }
		$this->uses = $uses;
	}


	/*
	 *
	 * Note: The "Styles" service is an extended Messages class! (OneFile\Messages)
	 * It's NOT like the Laravel MessageBag class. The Messages class uses "Magic"
	 * methods like: add{MsgType}, get{MsgType} and del{MsgType} where "MsgType"
	 * can be anything.
	 *
	 */
	protected function render()
	{
		$html = '';

		foreach ($this->uses as $vendor)
		{
			// ADD REQUIRED VENDOR STYLES
			$indent = 1;
			if ($vendor == 'bootstrap3') { $html .= indent($indent) . '<link href="' . __BOOTSTRAP3_CSS__ . '" rel="stylesheet">' . PHP_EOL; }
			if ($vendor == 'select')     { $html .= indent($indent) . '<link href="' . __SELECT_CSS__ . '" rel="stylesheet">' . PHP_EOL; }
			if ($vendor == 'custom')     { $html .= indent($indent) . '<link href="' . __CUSTOM_CSS__ . '" rel="stylesheet">' . PHP_EOL; }
		}

		if ($html) $html .= PHP_EOL;

		$stylesets = [];

		$type = $this->type;

		// GET EARLY (Before) STYLES
		$getStylesFn = "get${type}Early";
		$styleset = Styles::$getStylesFn();
		if ($styleset) $stylesets[] = $styleset;

		// GET GENERAL STYLES
		$getStylesFn = "get$type";
		$styleset = Styles::$getStylesFn();
		if ($styleset) $stylesets[] = $styleset;

		// GET LATE (After) STYLES
		$getStylesFn = "get${type}Late";
		$styleset = Styles::$getStylesFn();
		if ($styleset) $stylesets[] = $styleset;

		if (! empty($stylesets))
		{
			$indent = 1;
			$html .= indent($indent) . '<style>' . PHP_EOL;

			$indent++;
			foreach ($stylesets as $styleset)
			{
				// RENDER STYLE SET
				foreach ($styleset as $csscode) $html .= indent($indent) . $csscode . PHP_EOL;
			}

			$indent--;
			$html .= indent($indent) . '</style>';
		}

		return $html ? trim($html) . PHP_EOL : '<!-- Vendor Styles: None -->' . PHP_EOL;
	}


	public function __toString()
	{
		return $this->render();
	}

}

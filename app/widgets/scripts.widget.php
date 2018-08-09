<?php

/*
 *
 * SCRIPTS WIDGET CLASS
 * By: C. Moller - 20 Feb 2016
 * Updated: 23 Apr 2016 - Made render() method "type" specific
 *
 */

class ScriptsWidget {

	protected $type;
	protected $uses;
	protected $indent;


	/*
	 *
	 * @param string $type values: GlobalScripts, LocalScripts
	 * @param string|array $uses Names of vendor libs to include
	 *
	 */
	public function __construct($type = 'GlobalScripts', $uses = null, $indent = 0)
	{
		$this->type = $type;
		if (! is_array($uses)) { $uses = array($uses); }
		$this->uses = $uses;
		$this->indent = $indent;
	}


	/*
	 *
	 * Note: The "Scripts" service is an extended Messages class! (OneFile\Messages)
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
			// ADD REQUIRED VENDOR SCRIPTS
			$indent = $this->indent;
			if ($vendor == 'jquery')    { $html .= indent($indent) . '<script src="' . __JQUERY__ . '"></script>' . PHP_EOL; }
			if ($vendor == 'bootstrap') { $html .= indent($indent) . '<script src="' . __BOOTSTRAP_JS__ . '"></script>' . PHP_EOL; }
			if ($vendor == 'select')    { $html .= indent($indent) . '<script src="' . __SELECT_JS__ . '"></script>' . PHP_EOL; }
		}

		if ($html) $html .= PHP_EOL;

		$scriptsets = [];

		$type = $this->type;

		// GET EARLY (Before) SCRIPTS
		$getScriptsFn = "get${type}Early";
		$scriptset = Scripts::$getScriptsFn();
		if ($scriptset) $scriptsets[] = $scriptset;


		// GET GENERAL SCRIPTS
		$getScriptsFn = "get$type";
		$scriptset = Scripts::$getScriptsFn();
		if ($scriptset) $scriptsets[] = $scriptset;

		// GET LATE (After) SCRIPTS
		$getScriptsFn = "get${type}Late";
		$scriptset = Scripts::$getScriptsFn();
		if ($scriptset) $scriptsets[] = $scriptset;

		$isGlobal = ($this->type == 'GlobalScripts');

		if ( ! empty($scriptsets))
		{
			$indent = $this->indent;
			$html .= indent($indent) . '<script>' . PHP_EOL;

			$indent++;

			if ($isGlobal) $html .= indent($indent) . 'var GlobalScripts = { run: function() {' . PHP_EOL;
			else  $html .= indent($indent) . 'var LocalScripts = { run: function() {' . PHP_EOL;

			$indent++;
			$html .= indent($indent) . 'console.log("Inline ' . ($isGlobal ? 'GlobalScripts.run()' : 'LocalScripts.run()') . '...");' . PHP_EOL;
			foreach ($scriptsets as $scriptset)
			{
				// RENDER SCRIPT SET
				foreach ($scriptset as $script) $html .= indent($indent) . $script . PHP_EOL;
			}

			$indent--;
			$html .= indent($indent) . '}};' . PHP_EOL;

			$indent--;
			$html .= indent($indent) . '</script>';
		}

		return $html ? trim($html) . PHP_EOL : ($isGlobal ? '<!-- Global Scripts: None -->' : '<!-- Local Scripts: None -->') . PHP_EOL;
	}


	public function __toString()
	{
		return $this->render();
	}

}

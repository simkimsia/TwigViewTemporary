<?php

App::import('View', 'TwigView.Twig');

class TwigThemeView extends TwigView {
	
	/**
	 * Load Twig Theme
	 */
	function __construct(&$controller, $register = true) {
		
		parent::__construct($controller, $register);
		$this->theme = & $controller->theme;
	}
	
	/**
 * Return all possible paths to find view files in order
 *
 * @param string $plugin The name of the plugin views are being found for.
 * @param boolean $cached Set to true to force dir scan.
 * @return array paths
 * @access protected
 * @todo Make theme path building respect $cached parameter.
 */
	function _paths($plugin = null, $cached = true) {
		$paths = parent::_paths($plugin, $cached);
		$themePaths = array();

		if (!empty($this->theme)) {
			$count = count($paths);
			for ($i = 0; $i < $count; $i++) {
				if (strpos($paths[$i], DS . 'plugins' . DS) === false
					&& strpos($paths[$i], DS . 'libs' . DS . 'view') === false) {
						if ($plugin) {
							$themePaths[] = $paths[$i] . 'Themed'. DS . $this->theme . DS . 'plugins' . DS . $plugin . DS;
						}
						$themePaths[] = $paths[$i] . 'Themed'. DS . $this->theme . DS;
					}
			}
			$paths = array_merge($themePaths, $paths);
		}
		return $paths;
	}
	
	
}
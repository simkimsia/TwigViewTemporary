<?php
/**
 * TwigView for CakePHP
 *
 * About Twig
 *  http://www.twig-project.org/
 *
 * @version 0.5
 * @package app.views
 * @subpackage app.views.twig
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @link http://github.com/m3nt0r My GitHub
 * @link http://twitter.com/m3nt0r My Twitter
 * @license MIT License
 */
if (!defined('TWIG_VIEW_CACHE')) {
	define('TWIG_VIEW_CACHE', APP.'Plugin'.DS.'TwigView'.DS.'tmp'.DS.'views');
}

$twigPath = CakePlugin::path('TwigView');

require_once(APP . 'Plugin' . DS . 'TwigView' . DS . 'Vendor' . DS . 'Twig' . DS . 'lib' . DS . 'Twig' . DS . 'Autoloader.php');
Twig_Autoloader::register();

require_once(APP . 'Plugin' . DS . 'TwigView' . DS .'Vendor' . DS .'Twig-extensions' . DS .'lib' . DS .'Twig' . DS . 'Extensions' . DS . 'Autoloader.php');
Twig_Extensions_Autoloader::register();

// overwrite twig classes (thanks to autoload, no problem)
require_once($twigPath . 'Lib' . DS . 'Twig_Node_Element.php');
require_once($twigPath . 'Lib' . DS . 'Twig_Node_Trans.php');
require_once($twigPath . 'Lib' . DS . 'Twig_TokenParser_Trans.php');

// my custom cake extensions
require_once($twigPath . 'Lib' . DS . 'Twig_Extension_I18n.php');
require_once($twigPath . 'Lib' . DS . 'Twig_Extension_Ago.php');
require_once($twigPath . 'Lib' . DS . 'Twig_Extension_Basic.php');
require_once($twigPath . 'Lib' . DS . 'Twig_Extension_Number.php');

// get twig core extension (overwrite trans block)
require_once($twigPath . 'Lib' . DS . 'CoreExtension.php');

require_once($twigPath . 'Lib' . DS . 'ExtensionIncludes.php');


/**
 * TwigView for CakePHP
 * 
 * @version 0.5
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @link http://github.com/m3nt0r/cakephp-twig-view GitHub
 * @package app.views
 * @subpackage app.views.twig
 */
class TwigView extends View {

/**
 * THEME_FOLDER constant
 *
 * @var string 
 */
   const THEME_FOLDER = 'Themed';

/**
 * File extension
 *
 * @var string
 */
	public $ext = '.tpl';

/**
 * Twig Environment Instance
 *
 * @var Twig_Environment
 */
	public $Twig;

/**
 * Collection of paths. 
 * These are stripped from $___viewFn.
 *
 * @todo overwrite getFilename()
 * @var array
 */
	public $templatePaths = array();

/**
 * Constructor
 * Overridden to provide Twig loading
 *
 * @param Controller $Controller Controller
 */
	public function __construct(Controller $Controller) {
		
		parent::__construct($Controller);

		if (isset($Controller->theme)) {
			$this->theme =$Controller->theme;
		}
		
		$this->templatePaths = App::path('View');
		$loader = new Twig_Loader_Filesystem($this->templatePaths[0]);
		$twigEnvironmentOptions = array(
			'cache' => TWIG_VIEW_CACHE,
			'charset' => strtolower(Configure::read('App.encoding')),
			'auto_reload' => Configure::read('debug') > 0,
			'autoescape' => false,
			'debug' => Configure::read('debug') > 0
		);
		
		// If a theme is used set theme_folder and theme name options
        if (isset($this->theme)) {
            $twigEnvironmentOptions['theme_folder'] = self::THEME_FOLDER;
            $twigEnvironmentOptions['theme']        = $this->theme;
        }
		
		//$this->Twig = new Twig_Environment($loader, $options);
		$this->Twig = new Ombi60_Twig_Environment($loader, $twigEnvironmentOptions);

		$this->Twig->addExtension(new CoreExtension);
		$this->Twig->addExtension(new Twig_Extension_I18n);
		$this->Twig->addExtension(new Twig_Extension_Ago);
		$this->Twig->addExtension(new Twig_Extension_Basic);
		$this->Twig->addExtension(new Twig_Extension_Number);

		// include the Debug extension
		$this->Twig->addExtension(new Twig_Extensions_Extension_Debug);

		$this->Twig->addExtension(new Ombi60_Twig_Extension);

		$this->ext = '.tpl';
	}

/**
 * Render the view
 *
 * @param string $___viewFn 
 * @param string $___dataForView 
 * @return void
 */
	protected function _render($___viewFn, $___dataForView = array()) {
		$isCtpFile = (substr($___viewFn, -3) === 'ctp');

		if (empty($___dataForView)) {
			$___dataForView = $this->viewVars;
		}

		if ($isCtpFile) {
			$out = parent::_render($___viewFn, $___dataForView);
		} else {
			ob_start();
			// Setup the helpers from the new Helper Collection
			$helpers = array();
			$loaded_helpers = $this->Helpers->attached();
			foreach($loaded_helpers as $helper) {
				$name = Inflector::variable($helper);
				$helpers[$name] =& $this->loadHelper($helper);
			}

			$data = array_merge($___dataForView, $helpers);	
			$data['_view'] = $this;

			$relativeFn = str_replace($this->templatePaths, '', $___viewFn);
			$template = $this->Twig->loadTemplate($relativeFn);
			echo $template->render($data);
			$out = ob_get_clean();
		}

		return $out;
	}

/**
 * Render an element
 *
 * @param string $name Element Name
 * @param array $params Parameters
 * @param boolean $callbacks Fire callbacks
 * @return string
 */
	public function element($name, $params = array(), $callbacks = false) {
		// email hack
		if (substr($name, 0, 5) != 'email') {
			$this->ext = '.ctp'; // not an email, use .ctp
		}

		$return = parent::element($name, $params, $callbacks);
		$this->ext = '.tpl';
		return $return;
	}


}
<?php

/**
 * Wrapper to $this->Html->image(UP_ONE_DIR_LEVEL . PRODUCT_IMAGES_THUMB_SMALL_URL . $image['ProductImage']['filename'],
											array('id'=>'small_'.$key));
 */
function ombi60ProductImgUrl($filename, $size) {
        /*
        $html = new HtmlHelper();
        return $html->image(UP_ONE_DIR_LEVEL . PRODUCT_IMAGES_THUMB_URL . $size . '/' . $filename);
        */
		if (empty($filename)) {
			$filename = 'no-image-' . $size . '.gif';
			return '/img/admin/' . $filename;
		}
		
        return '/' . PRODUCT_IMAGES_THUMB_URL . $size . '/' . $filename;
}

/**
 * Wrapper to $this->Html->image(UP_ONE_DIR_LEVEL . PRODUCT_IMAGES_THUMB_SMALL_URL . $image['ProductImage']['filename'],
											array('id'=>'small_'.$key));
 */
function ombi60MoneyWithCurrency($price) {
        App::import('Model', 'Shop');
	$money = Shop::get('ShopSetting.money_in_html_with_currency');
	App::import('Helper', 'Number');
    if (empty($view)) {
        $view = new View(new Controller(new CakeRequest()));
    }
    $number = new NumberHelper($view);
	
	if (strpos($money, '{{amount}}') !== false) {
		$price = $number->precision($price, 2);
		$price = str_replace('{{amount}}', $price, $money);
	} else if (strpos($money, '{{amount_with_no_decimals}}') !== false){
		$price = $number->precision($price, 0);
		$price = str_replace('{{amount_with_no_decimals}}', $price, $money);
	} 
	
	return $price;
}

function ombi60Money($price) {
        App::import('Model', 'Shop');
	$money = Shop::get('ShopSetting.money_in_html');
	App::import('Helper', 'Number');
    if (empty($view)) {
        $view = new View(new Controller(new CakeRequest()));
    }
    $number = new NumberHelper($view);
	
	if (strpos($money, '{{amount}}') !== false) {
		$price = $number->precision($price, 2);
		$price = str_replace('{{amount}}', $price, $money);
	} else if (strpos($money, '{{amount_with_no_decimals}}') !== false){
		$price = $number->precision($price, 0);
		$price = str_replace('{{amount_with_no_decimals}}', $price, $money);
	} 
	
	return $price;
}


/**
 * this is for cakephp framework
 * Web path to the CSS files directory.
 */
if (!defined('CSS_URL')) {
	define('CSS_URL', 'css/');
}

if (!defined('JS_URL')) {
	define('JS_URL', 'js/');
}

/*
if (!defined('ASSETS_URL')) {
	define('ASSETS_URL', 'assets/');
}
* we have removed the assets inside webroot and just use webroot directly
*/
if (!defined('ASSETS_URL')) {
	define('ASSETS_URL', '');
}


if (!defined('IMAGES_URL')) {
	define('IMAGES_URL', 'img/');
}

if (!defined('SNIPPETS_URL')) {
	define('SNIPPETS_URL', 'Snippets/');
}

function ombi60SnippetsUrl($filename) {
	// we need to set the theme as well
	App::import('Model', 'Shop');
	$shopId = Shop::get('Shop.id');
	$currentShop = Cache::read('Shop'.$shopId);
	$theme = !empty($currentShop['FeaturedSavedTheme']['folder_name']) ? $currentShop['FeaturedSavedTheme']['folder_name'] : 'blue-white';
	
	
		
	if ($filename[0] !== '/') {
		$filename = 'Themed' . DS. $theme . DS . SNIPPETS_URL . $filename;
	}
	
	if (strpos($filename, '?') === false) {
		if (substr($filename, -4) !== '.tpl') {
			$filename .= '.tpl';
		}
	}
	
	
	return $filename;
	
	
}

function ombi60AssetUrl($filename) {
	// we need to use HtmlHelper existing in cakephp currently
	// in future we may use cdn otherwise or whatever
    App::import('Helper', 'Html');
    $view = Configure::read('Twig.View');
    if (empty($view)) {
        $view = new View(new Controller(new CakeRequest()));
    }
    $htmlHelper = new HtmlHelper($view);
	
	// we need to set the theme as well
	App::import('Model', 'Shop');
	$shopId = Shop::get('Shop.id');
	$currentShop = Cache::read('Shop'.$shopId);
	$htmlHelper->theme = !empty($currentShop['FeaturedSavedTheme']['folder_name']) ? $currentShop['FeaturedSavedTheme']['folder_name'] : 'blue-white';
	
	// we need to differentiate between css, js and image files
	// check for .css, .js, and all possible image extensions based on the filename.
	if (preg_match("/\.css$/", $filename)) {
		
		$themeTemplate = false;
		if ($filename == 'theme.css') {
			$filename = $filename . '.tpl';
			$themeTemplate = true;
		}
		
		if ($filename[0] !== '/') {
			$filename = ASSETS_URL . $filename;
		}
		
		if (strpos($filename, '?') === false) {
			$fileExtension = substr($filename, -4);
			if ($fileExtension !== '.css' && !$themeTemplate) {
				$filename .= '.css';
			}
		}
		$url = $htmlHelper->assetTimestamp($htmlHelper->webroot($filename));
		
        if (substr($url, 0, 1) != '/')
            $url = '/' . $url;
		return $url;
	}
	
	
	if (preg_match("/\.js$/", $filename)) {
		
		if ($filename[0] !== '/') {
			$filename = ASSETS_URL . $filename;
		}

		if (strpos($filename, '?') === false) {
			if (substr($filename, -3) !== '.js') {
				$filename .= '.js';
			}
		}
		$url = $htmlHelper->assetTimestamp($htmlHelper->webroot($filename));

		if (substr($url, 0, 1) != '/')
		    $url = '/' . $url;
		return $url;
	}
	// should be limited to these file extensions for time being
	if (preg_match("/\.png|.jpg|.jpeg|.gif|.tiff$/", $filename)) {
		
		if ($filename[0] !== '/') {
			$filename = ASSETS_URL . $filename;
		}
		
		
		$url = $htmlHelper->assetTimestamp($htmlHelper->webroot($filename));
		
        if (substr($url, 0, 1) != '/')
            $url = '/' . $url;
		return $url;
	}
}

/**
 * returns <img src="url...", alt="" />
 **/
function ombi60ImgTag($url, $alt="") {
	// keep it simple, no need to escape characters or check if image exists
	return '<img src="'.$url.'" alt="'.$alt.'" />';
}

/**
 * returns <script src="url..."/>
 **/
function ombi60ScriptTag($url) {
	return '<script  type="text/javascript" src="'.$url.'"></script>';
}

/**
 * returns <script src="url..."/>
 **/
function ombi60CssTag($url) {
	return '<link rel="stylesheet" type="text/css" href="'.$url.'" />' ;
}

function ombi60LinkTo($title, $url, $titleAttribute="") {
	// we need to use HtmlHelper existing in cakephp currently
	// in future we may use cdn otherwise or whatever
	App::import('Helper', 'Html');
    if (empty($view)) {
        $view = new View(new Controller(new CakeRequest()));
    }
    $htmlHelper = new HtmlHelper($view);
	
	return $htmlHelper->link($title, $url, array('title'=>$titleAttribute));
}

function ombi60Camelize($input) {
	$input = ucwords($input);
	$input = preg_replace("/[^a-zA-Z0-9]/", "", $input);
	return lcfirst($input);
}

function ombi60Handle($input) {
	$input = strtolower($input);
	$input = str_replace(' ', '-', $input);
	while (strpos($input, '--') !== false) {
		$input = str_replace('--', '-', $input);
	}
	$input = preg_replace("/[^a-zA-Z0-9\-]/", "", $input);
	return $input;
}

function ombi60Implode($glue, $pieces) {
	return implode($glue, $pieces);
}

function ombi60Pluralize($number, $singular, $plural) {
	if ($number == 1) {
		return $number . ' ' . $singular;
	} else {
		return $number . ' ' . $plural;
	}
}

function ombi60TruncateString($input, $length = 100, $endsWith = '...') {
	return substr($input, 0, $length) . $endsWith;
}

function ombi60TruncateWords($input, $words = 15, $endsWith = '...') {
	$tok = strtok($input, " \n\t");
	$count = 0;
	$result = '';
	while ($tok !== false && $count <= $words) {
		$count += 1;
		$result .= $tok;
		$tok = strtok(" \n\t");
	}
	
	return $result;
}

function ombi60WeightWithUnit($weight_in_grams) {
	App::import('Model', 'Shop');
	$unit = Shop::get('ShopSetting.unit_system');
	
	App::import('Helper', 'Number');
    if (empty($view)) {
        $view = new View(new Controller(new CakeRequest()));
    }
    $number = new NumberHelper($view);

	$result_weight = 0.0;
	if ($unit === 'metric') {
		$result_weight =  $weight_in_grams * 0.001;
		return $number->precision($result_weight, 1) . ' kg';
	} else {
		$result_weight =  $weight_in_grams * 0.00220462262;
		return $number->precision($result_weight, 1) . ' lb';
	}
}

function ombi60StripNewlines($input) {
	return (string)str_replace(array("\r", "\r\n", "\n"), '', $input);
}

function ombi60StripHtml($input) {
	return (string)strip_tags($input);
}

function ombi60PutProductUrlWithinCollection($product_url, $collection) {
	$isCollectionValid = !empty($collection['url']);
	if ($isCollectionValid) {
		return $collection['url'] . $product_url;
	}
}

/**
 * Product Image Url
 * Use: {{ product.images[0] | product_img_url : 'large' }}
 *
 * @author kimsia <kimcity@gmail.com>
 */
class Ombi60_Twig_Extension extends Twig_Extension
{
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            // {% for value in slice(values, limit, offset) %}
            'slice' => new Twig_Function_Method($this, 'slice'),
        );
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'product_img_url' => new Twig_Filter_Function('ombi60ProductImgUrl'),
	    'money_with_currency' => new Twig_Filter_Function('ombi60MoneyWithCurrency'),
	    'money' => new Twig_Filter_Function('ombi60Money'),
	    'asset_url' => new Twig_Filter_Function('ombi60AssetUrl'),
	    'img_tag' => new Twig_Filter_Function('ombi60ImgTag'),
	    'script_tag' => new Twig_Filter_Function('ombi60ScriptTag'),
	    'css_tag' => new Twig_Filter_Function('ombi60CssTag'),
	    'link_to' => new Twig_Filter_Function('ombi60LinkTo'),
	    'camelize' => new Twig_Filter_Function('ombi60Camelize'),
	    'handleize' => new Twig_Filter_Function('ombi60Handle'),
	    'implode' => new Twig_Filter_Function('ombi60Implode'),
	    'pluralize' => new Twig_Filter_Function('ombi60Pluralize'),
	    'truncate' => new Twig_Filter_Function('ombi60TruncateString'),
	    'truncatewords' => new Twig_Filter_Function('ombi60TruncateWords'),
	    'weight_with_unit' => new Twig_Filter_Function('ombi60WeightWithUnit'),
	    'strip_newlines' => new Twig_Filter_Function('ombi60StripNewlines'),
	    'strip_html' => new Twig_Filter_Function('ombi60StripHtml'),
	    'json' => new Twig_Filter_Function('json_encode'), // this is for converting data for use in JS see http://wiki.shopify.com/Json
	    'within' => new Twig_Filter_Function('ombi60PutProductUrlWithinCollection'),
	    'snippets_url' => new Twig_Filter_Function('ombi60SnippetsUrl'),
        );
    }

    /**
     * @param array $array
     * @param int   $limit
     * @param int   $offset
     *
     * @return array
     */
    public function slice($array, $limit = null, $offset = 0)
    {
        return array_slice($array, $offset, $limit, true);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ombi60';
    }
}

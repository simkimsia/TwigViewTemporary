<?php
/**
 * Wrapper to __()
 */
function cakeI18nFunction($var) {
	return __($var);
}

/**
 * This file is part of Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * Modified to use CakePHP functions
 *    @author Kjell Bublitz <m3nt0r.de@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Twig_Extension_I18n extends Twig_Extension
{
    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return array(new Twig_TokenParser_Trans());
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'trans' => new Twig_Filter_Function('cakeI18nFunction'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'i18n';
    }
}

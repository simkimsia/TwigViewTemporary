<?php

class Ombi60_Twig_Environment extends Twig_Environment
{
    protected $themeFolder;
    protected $theme;

    /**
     * Available options:
     *  * theme
     *  * theme_folder
     *
     * {@inheritDoc}
     */
    public function __construct(Twig_LoaderInterface $loader = null, $options = array())
    {
        if (isset($options['theme'])) {
            $this->themeFolder = $options['theme_folder'];
            $this->theme       = $options['theme'];

            unset($options['theme'], $options['theme_folder']);
        }

        parent::__construct($loader, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function loadTemplate($name)
    {
        // $name = /NameOfThemeFolder/NameOfCurrentTheme/template.tpl

        // If the template path starts by /NameOfThemeFolder/ or NameOfThemeFolder/ == TwigView::THEME_FOLDER constant
        if (preg_match('#^/?'.preg_quote($this->themeFolder).'/(.*)$#', $name, $match)) {
            // If the template belongs to another theme
            if (!preg_match('#^'.preg_quote($this->theme).'#', $match[1])) {
                // throw an InvalidArgumentException
                throw new InvalidArgumentException(sprintf('The template "%s" cannot be included for the theme "%s"', $name, $this->theme));
            }
        }

        // else if the template belongs at the same theme or does not belong to a theme
        // return the result of parent::loadTemplate()
        return parent::loadTemplate($name);
    }
}
<?php

$twigPath = CakePlugin::path('TwigView');

// overwrite Twig_Environment class with project specific Twig_Environment.php
//require_once($twigPath . 'Lib' . DS . 'Project_Twig_Environment.php');

// custom project Twig_Extension class
//require_once($twigPath . 'Lib' . DS . 'Project_Twig_Extension.php');

require_once($twigPath . 'Lib' . DS . 'Ombi60_Twig_Environment.php');
require_once($twigPath . 'Lib' . DS . 'Ombi60_Twig_Extension.php');
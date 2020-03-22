<?php

namespace Application\Lib;

use Twig_Autoloader;
use Twig_Environment;
use Twig_Loader_Filesystem;

class StatusPage
{
    /**
     * @var Twig_Environment
     */
    private static $twig;

    static function error404($displayMessage)
    {

        self::displayPage('status404.twig', $displayMessage);

    }

    private static function displayPage($twigPage, $message)
    {
        self::init();
        $error404 = self::$twig->loadTemplate($twigPage);
        print $error404->render(array('errortext' => $message));
        exit;
    }

    /**
     * @param $displayText
     * the message to display to the user
     */
    static function init()
    {

        //twig template engine
        Twig_Autoloader::register();
        //define the path to the page folder , in this case it is ../page/ relative to
        // the controller directory
        $page_path = array(
            __DIR__,
            '..',
            'Page',
            'Templates',
            'StatusPage'
        );
        $twig_template_path = implode(DIRECTORY_SEPARATOR, $page_path);
        $loader = new Twig_Loader_Filesystem($twig_template_path);
        self::$twig = new Twig_Environment($loader, array('debug' => true));
    }

    static function error500($displayMessage)
    {
        self::displayPage('status500.twig', $displayMessage);

    }

    static function Maintenance($displayMessage)
    {
        self::displayPage('statusMaintenance.twig', $displayMessage);

    }

}

?>
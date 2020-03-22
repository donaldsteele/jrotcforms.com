<?php

namespace Application\Page;

use Application\Lib\NoCSRF;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

class Base
{


    protected $_arguments;

    protected $http;
    protected $statusPage;

    protected $_pageVariables;
    protected $_pageScriptVariables;
    protected $_pageSecurityVariables;

    function __construct($title, $generateCSRFToken = false)
    {

        $this->AddPageVariable('PageTitle', $title);
        $this->AddSecurityVariable('csrfToken', NoCSRF::generate('csrf_token'));
    }

    public function AddPageVariable($key, $value)
    {
        $this->_pageVariables[$key] = $value;
    }

    public function AddSecurityVariable($key, $value)
    {
        $this->_pageSecurityVariables[$key] = $value;
    }

    public Function AddScriptVariable($value)
    {
        $this->_pageScriptVariables[] = $value;
    }

    protected function render($template)
    {

        $page_path = array(
            __DIR__,
            '..',
            'Page',
            'Templates'
        );


        $twig_template_path = implode(DIRECTORY_SEPARATOR, $page_path);
        $loader = new Twig_Loader_Filesystem($twig_template_path);
        $twig = new Twig_Environment($loader, array('debug' => true));
        $twig->addExtension(new Twig_Extension_Debug());


        $body = $twig->loadTemplate($template);
        $templateVars = ['page' => $this->_pageVariables, 'scripts' => $this->_pageScriptVariables, 'security' => $this->_pageSecurityVariables];
        print $body->render($templateVars);
    }

}
<?php

use Application\{
    CONFIGURATION\ENVIRONMENTTYPE, CONFIGURATION, Controller\DefaultController, Lib\StatusPage
};
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class AppMain
{

    /**
     * AppMain constructor.
     * @param $intent string  valid values [page , script]
     */
    function __construct($intent)
    {

        require_once('config.php');
        ob_start();
        session_start();


        //autoloader to find the class we want when new is called on it.
        spl_autoload_register(function ($class) {
            $parts = explode('\\', $class);

            $classFile = __DIR__ . DIRECTORY_SEPARATOR . array_shift($parts) . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) . ".php";
            //print "\n trying to load $classFile \n";
            if (FALSE === stream_resolve_include_path($classFile)) {
                return;
            }

            /** @noinspection PhpIncludeInspection */
            require $classFile;
        });

        $composerPath = array(
            __DIR__,
            'composer',
            'vendor',
            'autoload.php'
        );
        require_once(implode(DIRECTORY_SEPARATOR, $composerPath));



        //exception handler

        set_exception_handler(array(
            $this,
            'exception_handler'
        ));

        if ($intent == "page") {
            $this->route();
        }

        $defaultLogger = new Logger('defaultLogger');
        $defaultLogger->pushHandler(new StreamHandler('/tmp/dblog.txt', Logger::DEBUG));



        ob_end_flush();
    }

    /**
     * @param $exception Exception
     */
    function exception_handler($exception)
    {

        if (CONFIGURATION::ENVIRONMENT !== ENVIRONMENTTYPE::PROD) {
            echo "<div><code>Uncaught exception: ", $exception->getMessage(), "\n<code></div>";
            var_dump(debug_backtrace());
            error_log("<div><code>Uncaught exception: " . $exception->getMessage() . "\n</code>/div>");
        }

        StatusPage::error500($exception->getMessage());

        exit;


    }


    /**
     * Route to the proper controller
     *
     * Attempt to load the proper controller class and pass control, if class is not
     * found then display the default controller
     *
     * @throws Exception
     * @internal param $void
     *
     */
    function route()
    {
        if (CONFIGURATION::MAINTENANCE === true) {
            StatusPage::Maintenance("We will be back soon");
        }


        if (isset($_SERVER['REQUEST_URI'])) {
            $parts = array_filter(explode('/', $_SERVER['REQUEST_URI']));


            $controller = array_shift($parts);

            /*
             * pop the first part off the url this should be our controller, lets check that
             * it is not a
             * file like a picture or icon file that is being requested, if it gets to here
             * then that means
             * the file does not exist
             * ASSUMPTION: a class name / controller can not contain a period
             */
            error_log("$controller", 0);
            if (strpos($controller, '.') !== false || (substr(end($parts), 0, 1) != '?' && strpos(end($parts), '.') !== false)) { //check the first and last elements for a period
                error_log("found a period!");
                error_log(print_r($parts, true));
                http_response_code(404);
                StatusPage::error404("The Page Requested " . $_SERVER['REQUEST_URI'] . " was not found");
                return;
            } else {
                $controller_class = '\\Application\\Controller\\' . $controller;
                $controller_params = array(
                    'PATHPARTS' => $parts,
                    'ROOT' => __DIR__
                );
            }
        } else {
            //fall through and set defaults (this is when a user hits /)
            $controller_class = '\\Application\\Controller\\DefaultController';
            $controller_params = array('ROOT' => __DIR__);
        }


        try {
            if (class_exists($controller_class, true)) {
                //use reflection to get an instance of our controller object and pass in the
                // params needed
                $arrayOfConstructorArgs = array($controller_params);
                $r = new ReflectionClass($controller_class);
                $page = $r->newInstanceArgs($arrayOfConstructorArgs);
                $page->go();
            } else {
                // non existent controller requested
                $page = new DefaultController($controller_params);
                $page->go();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}

?>

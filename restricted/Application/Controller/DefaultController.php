<?php
namespace Application\Controller;


use Application\Enumeration\requestMethod;
use Application\Lib\HTTP;
use Application\Page\DefaultPage;


class DefaultController extends Base
{
    private $args;

    function __construct($arguments)
    {
        //pass our arguments back to the parent for safe keeping
        $vArgs = func_get_args();
        // you can't just put func_get_args() into a function as a parameter
        call_user_func_array(array($this, 'parent::__construct'), $vArgs);

    }

    function go()
    {
                $page = new DefaultPage('Index', false);
                $page->go();
    }

}

?>
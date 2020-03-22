<?php

namespace Application\Controller;


class leadcampform extends Base
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
        $page = new \Application\Page\LeadCampForm('LEAD CAMP 2020', false);
        $page->go();
    }

}


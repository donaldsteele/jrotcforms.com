<?php
/**
 * Created by PhpStorm.
 * User: don
 * Date: 10/24/2016
 * Time: 8:35 PM
 */

namespace Application\Page;


class DefaultPage extends Base
{

    function __construct($title, $useCRF)
    {

        parent::__construct($title, $useCRF);

    }

    function go()
    {
        parent::render('Default.twig');
    }

}
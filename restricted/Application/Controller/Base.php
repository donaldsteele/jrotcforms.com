<?php
namespace Application\Controller;


use Application\Lib\StatusPage;
use Exception;

class Base implements \Application\Interfaces\iController
{
    protected $_arguments;

    protected $http;
    protected $statusPage;

    function __construct($arguments)
    {


        //define the path to the page folder , in this case it is ../page/ relative to
        // the controller directory

        $this->_arguments = $arguments;


    }


    public function go()
    {
        throw new Exception('do not call base controller go!');
    }

    /**
     * Returns the page template based on class name
     *
     * returns the lowest level path of a class name ex:
     * \Application\Controllers\MyPage will result in MyPage.tpl being returned.
     *
     * @param string $nameSpace,... the current name space to strip from the fill
     * classpath normal __NAMESPACE__
     *
     * @param string $className,... the class name normally __CLASS__
     *
     * @return string
     */
    protected function get_class_short_name($nameSpace, $className)
    {
        return str_replace($nameSpace, '', $className);

    }

    protected function invalid_url($errortext)
    {
        StatusPage::error404($errortext);
    }


}


?>
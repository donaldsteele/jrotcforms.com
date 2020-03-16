<?php

namespace Application\Lib;

use Application\Enumeration\HttpCode;
use Application\Enumeration\requestMethod;
use Exception;

class HTTP
{

    static function Redirect(string $url)
    {

        header("Location: $url", false);
    }

    /**
     * @param $num HttpCode|string
     *
     */
    static function sendHeader($num)
    {
        header($num, true);
    }

    static function requestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'PUT' :
                return requestMethod::PUT;
                break;
            case 'POST' :
                return requestMethod::POST;
                break;
            case 'GET' :
                return requestMethod::GET;
                break;
            case 'HEAD' :
                return requestMethod::HEAD;
                break;
            case 'DELETE' :
                return requestMethod::DELETE;
                break;
            case 'OPTIONS' :
                return requestMethod::OPTIONS;
                break;
            default :
                throw new Exception("Unable to determine http request type!");
                break;
        }
    }

}

?>
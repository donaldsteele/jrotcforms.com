<?php

namespace Application;

use Application\CONFIGURATION\DOWNLOADTYPE;
use Application\CONFIGURATION\ENVIRONMENTTYPE;
use Application\CONFIGURATION\WEBSERVERTYPE;

Class CONFIGURATION
{

    /**
     * @var boolean
     */
    const MAINTENANCE = false;
    /**
     * @var ENVIRONMENTTYPE
     */
    const ENVIRONMENT = ENVIRONMENTTYPE::DEV;

    const DOWNLOADPATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'users';


    const WEBSERVER=WEBSERVERTYPE::NGINX;
    //the virtual path configured in nginx to use X-Accel
    const NGINX_VIRTUAL_FILE_PATH='/users/';
    const SEND_FILE_STYLE=DOWNLOADTYPE::NGINX_X_Accel;


    const DB_STORAGE_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'restricted' . DIRECTORY_SEPARATOR . 'data.db3';

}

namespace Application\CONFIGURATION;
class ENVIRONMENTTYPE
{
    const PROD = 0x01;
    const UAT = 0x02;
    const DEV = 0x04;
}

class WEBSERVERTYPE {
    const APACHE = 0x01;
    const NGINX = 0x02;
}

class DOWNLOADTYPE {
    const APACHE_XSENDFILE = 0x01;
    const NGINX_X_Accel = 0x02;
    const PHP_RAW = 0x04;
}





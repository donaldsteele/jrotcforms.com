<?php
/**
 * Created by PhpStorm.
 * User: don
 * Date: 10/24/2016
 * Time: 6:44 PM
 */

namespace Application\Page;

use Application\Lib\HTTP;
use Application\Lib\Session;


class SecureBase extends Base
{
    protected $session;

    function __construct($title, $generateCSRFToken = false)
    {
        error_log(__CLASS__);
        $this->session = new Session();
        if ($this->session->is_logged_in() === false) {
            error_log('Redirecting');
            HTTP::Redirect('/login');
        }
        parent::__construct($title, $generateCSRFToken);

        $this->AddSecurityVariable('username', $this->session->username());
        $this->AddSecurityVariable('logged_in', $this->session->is_logged_in());
        $this->AddSecurityVariable('userid', $this->session->userID());

    }


}
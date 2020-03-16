<?php
namespace Application\Lib;
use Application\Model\UsersQuery;

class Session
{


    private static $_instance;

    public function __construct()
    {
        if (!isset($_SESSION["logged_in"])) {
            $_SESSION["logged_in"] = false;
        }
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Session();
        }

        return self::$_instance;
    }

    public function username()
    {
        return $_SESSION['username'] ?? null;
    }

    public function userID()
    {

        return $_SESSION['id'] ?? null;
    }

    public function authToken()
    {
        return $_SESSION['authtoken'] ??  null;
    }

    public function is_logged_in()
    {
        error_log(print_r($_POST,true));
        error_log(print_r($_GET,true));
        if(isset($_POST["authtoken"]) || isset($_GET["authtoken"])) {
            error_log("authtoken is set");
            $auth = \Application\Model\AuthtokensQuery::create()
                ->findOneByToken($_POST["authtoken"] ?? $_GET["authtoken"]);
            error_log(print_r($auth,true));
            if (!$auth->isPrimaryKeyNull() && $auth->getCreationdate()->add(new \DateInterval('PT12H')) > new \DateTime()) {
                $user = \Application\Model\UsersQuery::create()
                    ->findOneByUserid($auth->getUserid());
                error_log("user is logged in now by authtoken");
                $_SESSION["logged_in"] = true;
                $_SESSION['username'] = $user->getUsername();
                $_SESSION['id'] = $user->getUserid();
                error_log( "Date Now");
                error_log( print_r(new \DateTime(),true));
                error_log( "Token Expires on");
                error_log(print_r($auth->getCreationdate()->add(new \DateInterval('PT4H')),true));
            } else {
                error_log("failed date check");
                error_log(print_r($auth->getCreationdate()->add(new \DateInterval('PT30M')),true));
                error_log( print_r(new \DateTime(),true));
            }
        }
        return $_SESSION["logged_in"] ?? null;
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function login($username, $password)
    {

        $validUser = false;

        $users = UsersQuery::create()
            ->filterByUsername($username)
            ->find();


        if ($users->count() == 1) {
            $user = $users->getFirst();
            /***
             * @var $user \Application\Model\Users
             */
            $db_pw_hash = $user->getPassword();
            if (password_verify($password, $db_pw_hash) === true) {
                $_SESSION["logged_in"] = true;
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $user->getUserid();
                $_SESSION['authtoken']=bin2hex(random_bytes(16));

                $auth = new \Application\Model\Authtokens();
                $auth->setUserid($_SESSION['id']) ;
                $auth->setToken($_SESSION['authtoken']);
                $auth->save();
                return true;
            } else {
                $_SESSION["logged_in"] = false;
                return false;
            }

        }
        return false;
    }


    public function logout()
    {

        // Unset all of the session variables.
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_regenerate_id(true);
        session_destroy();

        flush();
        ob_flush();

    }
}

?>

<?php
/**
 * Created by PhpStorm.
 * User: steeld2
 * Date: 2/7/2019
 * Time: 9:47 AM
 */

namespace Application\Model;


use Exception;
use PDO;

class base
{

    protected string $user;
    protected string $password;
    protected string $connectionsString;

    /**
     * @var PDO $conn
     */
    protected PDO $conn;


    /**
     * @var array
     */
    protected array $columns;

    /**
     * @var integer
     */
    protected int $recordcount;

    function __construct()
    {
        parent::__construct();

    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getConnectionsString()
    {
        return $this->connectionsString;
    }

    /**
     * @param mixed $connectionsString
     */
    public function setConnectionsString($connectionsString)
    {
        $this->connectionsString = $connectionsString;
    }

    /**
     * @return array
     */

    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return integer
     */
    public function getRecordcount()
    {
        return $this->recordcount;
    }

    function connect()
    {
        throw new Exception("Not Implimented");
    }

    function execute($sql)
    {
        throw new Exception("Not Implimented");
    }


    function arrayToBoundVars(array $data)
    {
        $out = array();
        foreach ($data as $key => $val) {
            $out[trim(':' . $key)] = $val;
        }
        return $out;
    }

    function arrayPrefixKeys($array, $prefix)
    {
        $out = [];
        foreach ($array as $k => $v) {
            $out[$prefix . $k] = $v;
        }
        return $out;

    }


    function columnListToInsertSQL(string $table, array $columnList, $insertIgnore = false)
    {

        $columns = implode(' , ', array_values($columnList));
        $values = implode(' , ', array_map(function ($k) {
            return ':' . $k;
        }, array_values($columnList)));

        /* you can pass 'OR IGNORE' for example as the $insertPrefix */
        $insertPrefix = '';
        if ($insertIgnore) {
            $insertPrefix = 'OR IGNORE ';
        }

        $sql = 'INSERT ' . $insertPrefix . 'INTO ' . $table . ' (' . $columns . ') VALUES (' . $values . ')';
        error_log(print_r($sql, true));

        return $sql;
    }


}
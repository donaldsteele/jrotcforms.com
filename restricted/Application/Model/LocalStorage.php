<?php


namespace Application\Model;

use Application\CONFIGURATION;
use Application\Model\Table\leadcampform;
use PDO;


class LocalStorage extends SQLite
{

    function __construct()
    {

        parent::__construct();
        $doCreate = !file_exists(CONFIGURATION::DB_STORAGE_PATH);
        $this->conn = new PDO('sqlite:' . CONFIGURATION::DB_STORAGE_PATH);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        if ($doCreate) {
            $lcf = new leadcampform();

            $this->executeNonQuery("PRAGMA foreign_keys = off; BEGIN TRANSACTION; " . $lcf->createStatement() . "COMMIT TRANSACTION; PRAGMA foreign_keys = on;");
        }
    }

    function saveTableCollection($tableObjectCollection)
    {

        $returns = [];
        foreach ($tableObjectCollection as $tableObject) {
            $returns[] = $this->saveTable();

        }
        return $returns;
    }

    /** @return string|null
     * @var $tableObject \Application\Model\Table\base
     */
    function saveTable($tableObject)
    {


        $sql = $this->columnListToInsertSQL($tableObject->tableName(), $tableObject->fieldList());
        $data = $this->arrayPrefixKeys($tableObject->toArray(), ":");
        return $this->executeNonQueryWithParams($sql, $data);
    }

    public function populateTable($sql, $object)
    {
        return $this->executeAsObjCollection($sql, $object);

    }


}



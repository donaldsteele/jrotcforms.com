<?php


namespace Application\Model;

use Exception;
use PDO;

class SQLite extends base
{

    /***
     * @var PDO
     */
    protected PDO $conn;


    private $CREATEDB = <<<_ENDSCRIPT
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: jobs
DROP TABLE IF EXISTS jobs;

CREATE TABLE jobs (
    jobID        INTEGER  PRIMARY KEY AUTOINCREMENT
                          NOT NULL,
    scriptID     INTEGER  REFERENCES scripts (scriptID) 
                          NOT NULL,
    creationDate DATETIME DEFAULT (CURRENT_TIMESTAMP),
    userID       INTEGER  REFERENCES users (userID) 
                          NOT NULL,
    suppliedVars TEXT
);


-- Table: jobStatus
DROP TABLE IF EXISTS jobStatus;

CREATE TABLE jobStatus (
    jobStatusID  INTEGER  PRIMARY KEY AUTOINCREMENT,
    jobID        INTEGER  REFERENCES jobs (jobID),
    message      TEXT,
    creationTime DATETIME DEFAULT (CURRENT_TIMESTAMP) 
);


-- Table: scripts
DROP TABLE IF EXISTS scripts;

CREATE TABLE scripts (
    scriptID          INTEGER       PRIMARY KEY AUTOINCREMENT,
    scriptName        VARCHAR (255) NOT NULL,
    scriptText        TEXT          NOT NULL,
    scriptDescription TEXT          NOT NULL,
    creationDate      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    isActive          BOOLEAN       DEFAULT '0'
                                    NOT NULL,
    versionNumber     INTEGER       NOT NULL
                                    DEFAULT (0),
    scriptUUID        CHAR (36)     DEFAULT (lower(hex(randomblob(4) ) ) || '-' || lower(hex(randomblob(2) ) ) || '-4' || substr(lower(hex(randomblob(2) ) ), 2) || '-' || substr('89ab', abs(random() ) % 4 + 1, 1) || substr(lower(hex(randomblob(2) ) ), 2) || '-' || lower(hex(randomblob(6) ) ) ) 
);


-- Table: users
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    userID   INTEGER      PRIMARY KEY AUTOINCREMENT,
    username VARCHAR (20) 
);


-- Index: uniqueScriptUUID
DROP INDEX IF EXISTS uniqueScriptUUID;

CREATE UNIQUE INDEX uniqueScriptUUID ON scripts (
    scriptUUID
);


-- Trigger: scriptVersionManager
DROP TRIGGER IF EXISTS scriptVersionManager;
CREATE TRIGGER scriptVersionManager
         AFTER INSERT
            ON scripts
BEGIN
    UPDATE scripts
       SET isActive = 0
     WHERE scriptName = new.scriptName AND 
           isActive = 1;
    UPDATE scripts
       SET versionNumber = (
               SELECT coalesce(max(versionNumber) + 1, 0) 
                 FROM scripts
                WHERE scriptName = new.scriptName
           ),
           isActive = 1
     WHERE scriptID = new.scriptID;
END;


-- View: SchemaInfo
DROP VIEW IF EXISTS SchemaInfo;
CREATE VIEW SchemaInfo AS
    SELECT 1.0 AS VersionNumber;


COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
_ENDSCRIPT;


    function __construct()
    {

        parent::__construct();

    }

    function connect()
    {

    }

    function execute($sql)
    {

        $result = $this->conn->query($sql);
        $records = $result->fetchAll();
        return $records;

    }

    function executeAsObjCollection($sql, $tableObject)
    {


        $result = $this->conn->query($sql);
        return $tableObject->toObjectCollection($result);


    }


    function executeNonQuery($sql)
    {

        $this->conn->exec($sql);

    }

    function executeQueryWithParams(string $sql, array $params)
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $records;

        } catch (Exception $e) {
            error_log(print_r($e, true));
        }
        return [];
    }

    function executeNonQueryWithParams($sql, $params)
    {

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            if ($stmt) {
                return $this->conn->lastInsertId();
            } else {
                return null;
            }
        } catch (Exception $e) {
            error_log(print_r($e, true));
        }
    }


}
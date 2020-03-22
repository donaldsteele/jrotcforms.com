<?php


namespace Application\Model\Table;


class leadcampform extends base
{

    public $camper_first_name;
    public $camper_last_name;
    public $camper_age;
    public $shirtsize;
    public $address;
    public $address2;
    public $city;
    public $state;
    public $zip;
    public $primary_first_name;
    public $primary_last_name;
    public $phone_number;
    public $email;
    public $emergency_first_name;
    public $emergency_last_name;
    public $emergency_phone_number;
    public $emergency_email;
    public $camp_duration;
    public $waiver_initials;
    public $insurance_company;
    public $policy_number;
    public $allergies;


    public function createStatement()
    {
        /* TODO: probably want to move this to a generic yaml configuration or something .
        if moved to yaml then we can reflect back in the required properties at runtime or build a generator to make this less of a thing to worry about.
        */

        $myName = $this->tableName();
        $statement = <<<_ENDSCRIPT
    CREATE TABLE IF NOT EXISTS ${myName} (
         leadcampformID INTEGER      PRIMARY KEY AUTOINCREMENT,
         camper_first_name        varchar(100),
         camper_last_name        varchar(100),
         camper_age        varchar(2),
         shirtsize        varchar(30),
         address        varchar(100),
         address2        varchar(100),
         city        varchar(100),
         state        varchar(30),
         zip        varchar(10),
         primary_first_name        varchar(100),
         primary_last_name        varchar(100),
         phone_number        varchar(100),
         email        varchar(200),
         emergency_first_name        varchar(100),
         emergency_last_name        varchar(100),
         emergency_phone_number        varchar(20),
         emergency_email        varchar(200),
         camp_duration        varchar(100),
         waiver_initials        varchar(10),
         insurance_company        varchar(100),
         policy_number        varchar(25),
         allergies        varchar(500),
         creationDate     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
_ENDSCRIPT;
        return $statement;
    }
}
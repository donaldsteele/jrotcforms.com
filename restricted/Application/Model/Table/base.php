<?php


namespace Application\Model\Table;


use PDOStatement;
use ReflectionClass;

class base
{
    public function toObjectCollection(PDOStatement $statement)
    {
        $objs = array();
        while ($obj = $statement->fetchObject(__CLASS__)) {
            $objs[] = $obj;
        }
        return $objs;
    }


    /**
     * @param $data array
     * Loads a row of data into the object
     */
    public function LoadFromArraySingle($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function fieldList()
    {
        $out = [];
        foreach ($this->toArray() as $k => $v) {
            print "mooo $k \n\n";
            $out[] = $k;
        }
        print_r($out);
        return $out;
    }

    public function toArray()
    {
        $out = [];
        foreach (get_object_vars($this) as $k => $v) {
            $out[$k] = $v ?? ""; // return blank string it not set
        }
        return $out;
    }

    public function tableName()
    {
        return (new ReflectionClass($this))->getShortName();
    }

}
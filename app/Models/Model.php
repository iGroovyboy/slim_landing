<?php


namespace App\Models;


class Model
{
    protected static $tableName;

    protected function setTableName($tableName) {
        $this->tableName = $tableName;
    }

}
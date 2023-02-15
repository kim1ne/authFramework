<?php

namespace App\Services\Db;

class Builder
{
    public static $sql;

    public static function query(string $sql)
    {
        $db = Db::getInstance();
        $db->sql($sql);
        return $db;
    }
}
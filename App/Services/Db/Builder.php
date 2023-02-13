<?php

namespace App\Services\Db;

class Builder
{
    public static function query(string $sql)
    {
        $db = new Db();
        $db->sql($sql);
        return $db->getObject();
    }
}
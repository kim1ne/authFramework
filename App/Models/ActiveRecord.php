<?php

namespace App\Models;

use App\Services\Db\Db;

abstract class ActiveRecord
{
    protected $id;
    protected $created_at;

    public function __get($name): mixed
    {
        return $this->$name;
    }

    public function set(string $name, string $value): void
    {
        $this->$name = $value;
    }

    private function getColumn(object $obj): array
    {
        $reflection = new \ReflectionClass($obj);
        $columns = [];
        foreach ($reflection->getProperties() as $prop) {
            if ($prop->name === 'id') continue;
            $columns[] = $prop->name;
        }
        return $columns;
    }

    public function save()
    {
        $this->set('created_at', date("Y-m-d H:i:s"));
        $columns = [];
        $values = [];
        foreach ($this->getColumn($this) as $propName) {
            $values[] = "'" . $this->$propName . "'";
            $columns[] = '`' . $propName . '`';
        }
        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ');';
        $db = new Db();
        $db->sql($sql);
        $db->execute();
        $this->id = $db->lastInsertId();
        return $this;
    }

    public static function all(): array
    {
        $sql = 'SELECT * FROM ' . static::getTableName() . ';';
        $db = new Db();
        $db->sql($sql);
        return $db->fetchAll();
    }

    public static function unique(string $column, $value): bool
    {
        $sql = "SELECT COUNT($column) as count FROM " . static::getTableName() . " WHERE login = '$value';";
        $db = new Db();
        $db->sql($sql);
        if ($db->fetch()['count'] <= 1) return false;
        return true;
    }

    public static function find(int $id): ?static
    {
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE id = :id";
        $db = new Db();
        $db->sql($sql);
        return $db->getObject([":id" => $id], static::class)[0] ?? null;
    }

    public static function findByColumn(string $column, string $value): ?static
    {
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE $column = :value";
        $db = new Db();
        $db->sql($sql);
        return $db->getObject([":value" => $value], static::class)[0] ?? null;
    }

    abstract public static function getTableName(): string;
}
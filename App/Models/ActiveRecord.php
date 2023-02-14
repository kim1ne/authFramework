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

    private function getColumn(): array
    {
        $unsetArray = ['id', 'created_at', 'updated_at'];
        $reflection = new \ReflectionClass($this);
        $columns = [];
        foreach ($reflection->getProperties() as $prop) {
            if (in_array($prop->name, $unsetArray)) continue;
            $columns[] = $prop->name;
        }
        return $columns;
    }

    public function save(): static
    {
        if ($this->id) {
            return $this->update();
        }

        return $this->add();
    }

    private function update(): static
    {
        $properties = $this->getColumn();
        $param2values = [];
        $column2params = [];
        $i = 1;
        foreach ($properties as $property) {
            $key = ":param" . $i;
            $column2params[] = $property . ' = ' . $key;
            $param2values[$key] = $this->$property;
            $i++;
        }

        $sql = "UPDATE " . static::getTableName() . " SET " . implode(', ', $column2params) . " WHERE id = " . $this->id;
        $db = Db::getInstance();
        $db->sql($sql);
        $db->execute($param2values);
        return $this;
    }

    private function add(): static
    {
        $columns = [];
        $values = [];
        $param2values = [];
        $i = 1;
        foreach ($this->getColumn() as $propName) {
            $key = ":param" . $i;
            $columns[] = $propName;
            $values[] = $key;
            $param2values[$key] = $this->$propName;
            $i++;
        }
        $columns[] = 'created_at';
        $values[] = ":param" . $i;
        $param2values[":param" . $i] = date("Y-m-d H:i:s");

        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ');';
        $db = Db::getInstance();
        $db->sql($sql);
        $db->execute($param2values);
        $this->id = $db->lastInsertId();
        return $this;
    }

    public static function all(): array
    {
        $sql = 'SELECT * FROM ' . static::getTableName() . ';';
        $db = Db::getInstance();
        $db->sql($sql);
        return $db->fetchAll();
    }

    public static function unique(string $column, $value): bool
    {
        $sql = "SELECT COUNT($column) as count FROM " . static::getTableName() . " WHERE login = '$value';";
        $db = Db::getInstance();
        $db->sql($sql);
        if ($db->fetch()['count'] <= 1) return false;
        return true;
    }

    public static function find(int $id): ?static
    {
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE id = :id";
        $db = Db::getInstance();
        $db->sql($sql);
        return $db->getObject([":id" => $id], static::class)[0] ?? null;
    }

    public static function findByColumn(string $column, string $value): ?static
    {
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE $column = :value";
        $db = Db::getInstance();
        $db->sql($sql);
        return $db->getObject([":value" => $value], static::class)[0] ?? null;
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM " . static::getTableName() . " WHERE id = :id";
        $db = Db::getInstance();
        $db->sql($sql);
        return $db->execute([':id' => $this->id]);
    }

    public static function remove(int $id)
    {
        $sql = "DELETE FROM " . static::getTableName() . " WHERE id = :id";
        $db = Db::getInstance();
        $db->sql($sql);
        return $db->execute([':id' => $id]);
    }

    abstract public static function getTableName(): string;
}
<?php

namespace App\Models;

use App\Services\Db\Db;

abstract class ActiveRecord implements \JsonSerializable
{
    const UNSET_PROPERTY = ['id', 'created_at', 'updated_at'];

    protected int $id;
    protected string $created_at;
    protected ?string $updated_at;

    public function __get($name): mixed
    {
        return $this->$name;
    }

    public function jsonSerialize(): array
    {
        $data = [];
        foreach ($this->getColumn() as $propName) {
            if ($this instanceof User && $propName === 'password') continue;
            $data[$propName] = $this->$propName ?? null;
        }
        return $data;
    }

    public function set(string $name, string $value): void
    {
        $this->$name = $value;
    }

    private function getColumn(): array
    {
        $reflection = new \ReflectionClass($this);
        $columns = [];
        foreach ($reflection->getProperties() as $prop) {
            $columns[] = $prop->name;
        }
        return $columns;
    }

    private function secureProp(): array
    {
        $columns = $this->getColumn();
        $new = [];
        foreach ($columns as $column) {
            if (in_array($column, self::UNSET_PROPERTY)) continue;
            $new[] = $column;
        }
        return $new;
    }

    public function save(): static
    {
        if (!empty($this->id)) {
            return $this->update();
        }

        return $this->add();
    }

    private function update(): static
    {
        $properties = $this->secureProp();
        $param2values = [];
        $column2params = [];
        $i = 1;
        foreach ($properties as $property) {
            $key = ":param" . $i;
            $column2params[] = $property . ' = ' . $key;
            $param2values[$key] = $this->$property;
            $i++;
        }

        $param2values[":param" . $i] = date("Y-m-d H:i:s");
        $key = ":param" . $i;
        $this->updated_at = date("Y-m-d H:i:s");
        $column2params[] = 'updated_at' . " = " . $key;

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
        foreach ($this->secureProp() as $propName) {
            $key = ":param" . $i;
            $columns[] = $propName;
            $values[] = $key;
            $param2values[$key] = $this->$propName;
            $i++;
        }
        $columns[] = 'created_at';
        $values[] = ":param" . $i;
        $this->created_at = date("Y-m-d H:i:s");
        $param2values[":param" . $i] = date("Y-m-d H:i:s");

        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ');';

        $db = Db::getInstance();
        $db->sql($sql);
        $db->execute($param2values);
        $this->id = $db->lastInsertId();
        return $this;
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM " . static::getTableName() . " WHERE id = :id";
        $db = Db::getInstance();
        $db->sql($sql);
        return $db->execute([':id' => $this->id]);
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

    public static function find(int $id): static|null
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

    public static function remove(int $id)
    {
        $sql = "DELETE FROM " . static::getTableName() . " WHERE id = :id";
        $db = Db::getInstance();
        $db->sql($sql);
        return $db->execute([':id' => $id]);
    }

    abstract public static function getTableName(): string;
}
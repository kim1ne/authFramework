<?php

namespace App\Services\Db\Orm;

use App\Services\Db\Orm\DataManager\Schema;

class Add extends Schema
{
    private string $table;
    private array $columns = [];
    private array $values = [];

    public function __construct(string $table)
    {
        $this->table = "`$table`";
    }

    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

    public function setValues(array $values)
    {
        $this->values = $values;
    }

    public function build()
    {
        $columns = '';
        $round = $this->columns;
        foreach ($round as $key => $column) {
            $columns .= "`$column`";
            unset($round[$key]);
            $symbol = '';
            if (count($round) > 0) $symbol = ', ';
            $columns .= $symbol;
        }
        $keyValues = array_keys($this->values);
        $limit = count($this->values[$keyValues[0]]);
        $params = [];
        for ($i = 0; $i < $limit; $i++) {
            foreach ($keyValues as $key => $column) {
                $params[$i][] = "'" . $this->values[$column][$i] . "'";
            }
        }

        $this->values = [];
        foreach ($params as $key => $value) {
            $this->values[] = '(' .  implode(', ', $value) . ')';
        }

        $sql = 'INSERT INTO ' . $this->table . " ($columns) VALUES " . implode(', ', $this->values);

        $sql .= ';';
        $this->sql = $sql;
        return $sql;
    }

}
<?php

namespace App\Services\Db\Orm;

use App\Services\Db\Orm\DataManager\Schema;

class Delete extends Schema
{
    private string $table;
    private array $condition;

    public function __construct(string $table)
    {
        $this->table = "`$table`";
    }

    public function setCondition(array $condition)
    {
        $this->condition = $condition;
    }

    public function build()
    {
        $sql = 'DELETE FROM ' . $this->table;

        if (empty($this->condition)) return $sql . ';';

        $sql .= ' WHERE ';

        foreach ($this->condition as $column => $condition) {

            $compare = '=';

            if (is_string($condition)) {
                if (in_array(substr($condition, 0, 1), $this->compare)) $compare = substr($condition, 0, 1);
            }

            if (is_array($condition)) {
                $compare = 'IN';
                $condition = $this->getQuerySnippet($condition);


                $condition = "($condition)";
            } else {
                $condition = "'" .  str_replace($compare, '', $condition) . "'";
            }

            $sql .= "$column $compare $condition";
        }

        $sql .= ';';
        $this->sql = $sql;
        return $sql;
    }
}
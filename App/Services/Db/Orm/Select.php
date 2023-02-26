<?php

namespace App\Services\Db\Orm;

use App\Services\Db\Orm\DataManager\Schema;

class Select extends Schema
{
    const INNER_JOIN = 'INNER JOIN';
    const LEFT_JOIN = 'LEFT JOIN';
    const RIGHT_JOIN = 'LEFT JOIN';

    private array $nullable = [
        'IS NULL', 'IS NOT NULL'
    ];

    protected string $table;
    protected string $sqlTable;


    protected array $where = [];

    protected array $join = [];
    protected array $joinTable = [];

    protected array $select = [];
    protected array $like = [];
    protected array $limit = [];
    protected string $order = '';

    public function __construct(string|array $table, array $select = ['*'])
    {
        $this->prepare($table, $select);
    }

    private function prepare(string|array $table, array $select)
    {
        if (is_string($table)) {
            $this->table = $table;
            $this->sqlTable = $table;
        }

        if (is_array($table)) {
            $key = array_key_first($table);
            $this->table = $table[$key];
            if (!is_int($key)) {
                $this->sqlTable = $key . " AS " . $table[$key];
            } else {
                $this->sqlTable = $this->table;
            }
        }

        foreach ($select as $key => $column) {
            if (is_int($key)) {
                $this->select[] = $this->table . ".$column";
            } else {
                $this->select[] = $this->table . ".$key AS $column";
            }
        }
    }

    public function where(array|string $data): self
    {
        $this->like = [];

        foreach ($data as $column => $condition) {
            if ($condition === 'null') {
                $condition = 'IS NULL';
            }
            if ($condition === '!null') {
                $condition = 'IS NOT NULL';
            }

            $this->where[$column] = $condition;

        }

        return $this;
    }

    public function select(array $data): self
    {
        foreach ($data as $select) {
            if (!strpos('.', $select)) {
                $table = $this->table;
                $this->select[] = "$table.$select";
            }
        }
        return $this;
    }

    public function orderBy(array $order)
    {
        $this->order = ' ORDER BY ';
        foreach ($order as $column => $sort) {
            $this->order .= "$column $sort";
            unset($order[$column]);
            $symbol = '';
            if (count($order) > 0) {
                $symbol = ', ';
            }
            $this->order .= $symbol;
        }
    }

    public function like(array $data)
    {
        foreach ($data as $column => $pattern) {
            $this->like[] = "$column LIKE '$pattern'";
        }

        return $this;
    }

    public function join(array|string $table, array $conditions, array $selects = [], string $typeJoin = 'INNER JOIN')
    {
        $this->like = [];

        $sqlTable = $table;
        if (is_array($table)) {
            $key = array_key_first($table);
            $table = $table[$key];
            $sqlTable = $key . " AS " . $table;
        }

        if (!empty($selects)) {
            foreach ($selects as $key => $select) {
                $choice = "$table.$select";
                $this->select[] = $choice;
            }
        } else {
            $this->select[] = "$table.*";
        }

        $on = '';

        foreach ($conditions as $column1 => $column2) {
            $on = "ON $column1 = $column2";
        }

        $sql = "$typeJoin $sqlTable $on";

        $this->joinTable[] = $table;

        $this->join[count($this->join)] = $sql;
    }

    public function limit(int $limit, int $offset = null)
    {
        if ($offset === null) {
            $page = $_GET['page'] ?? 1;
            $offset = ($page === 1) ? 0 : $limit * $page - $limit;
        }

        $this->limit = [
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    public function build()
    {
        $select = (empty($this->select)) ? '*' : implode(', ', $this->select);

        $sql = "SELECT $select FROM " . $this->sqlTable;

        if (!empty($this->join)) {
            $sql .= ' ' . implode(' ', $this->join);
            if (!empty($this->where)) {
                $sql .= ' HAVING ';
                $sql .= $this->whereIs();
            }
        }

        if (empty($this->join) && !empty($this->where)) {
            $sql .= ' WHERE ';
            $sql .= $this->whereIs();
        }

        if (!empty($this->like)) {
            $sql .= ' WHERE ' . $this->likeIs();
        }

        if (!empty($this->order)) {
            $sql .= ' ' . $this->order;
        }

        if (!empty($this->limit)) {
            $sql .= ' LIMIT ' . $this->limit['limit'] . ' OFFSET ' . $this->limit['offset'];
        }

        $sql .= ';';
        $this->sql = $sql;
        return $sql;
    }

    private function whereIs(): string
    {
        $sql = '';
        $whereArr = $this->where;

        if (empty($whereArr)) return '';

        foreach ($whereArr as $column => $condition) {

            $compare = '=';
            if (!is_int($condition)) {
                if (in_array($condition[0], $this->compare)) {
                    $compare = $condition[0];
                }
            }

            if (in_array($condition, $this->nullable)) $compare = '';

            $condition = str_replace($compare, '', $condition);

            if (is_array($condition)) {
                $compare = 'IN';
                $condition = $this->getQuerySnippet($condition);
                $condition = "($condition)";
            }
            $sql .= "$column $compare $condition";

            unset($whereArr[$column]);
            $symbol = '';
            if (count($whereArr) > 0) {
                $symbol = ' AND ';
            }
            $sql .= $symbol;
        }

        return $sql;
    }

    private function likeIs()
    {
        $sql = '';
        $likeArr = $this->like;

        if (empty($likeArr)) return '';

        foreach ($likeArr as $key => $like) {
            $sql .= $like;
            unset($likeArr[$key]);
            $symbol = ' ';
            if (count($likeArr) > 0) {
                $symbol = ', ';
            }
            $sql .= $symbol;
        }
        $this->sql = $sql;
        return $sql;
    }
}
<?php

namespace App\Services\Db;

class DataManager
{
    const INNER_JOIN = 'INNER JOIN';
    const LEFT_JOIN = 'LEFT JOIN';
    const RIGHT_JOIN = 'LEFT JOIN';

    protected string $table;
    protected string $sqlTable;
    protected string $sql;

    protected array $where = [];
    protected array $join = [];
    protected array $select = [];
    protected array $like = [];
    protected array $limit = [];

    public function __construct(array $table, array $select = ['*'])
    {
        $this->prepare($table, $select);
    }

    private function prepare(array $table, array $select)
    {
        $key = array_key_first($table);
        $this->table = $table[$key];
        if (!is_int($key)) {
            $this->sqlTable = $key . " AS " . $table[$key];
        } else {
            $this->sqlTable = $this->table;
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
        if (!empty($this->like)) {
            $this->like = [];
        }

        if (!empty($this->where)) {
            $this->where = array_merge($this->where, $data);
            return $this;
        }
        $this->where = $data;
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

    public function like(array $data)
    {
        if (!empty($this->where)) {
            $this->where = [];
        }
        if (!empty($this->like)) {
            $this->like = array_merge($this->like, $data);
            return $this;
        }
        $this->like = $data;
        return $this;
    }

    public function join(array|string $table, array $conditions, array $selects = [], string $typeJoin = 'INNER JOIN')
    {
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

        if (!empty($this->join) && !empty($this->where)) {
            $sql .= ' ' . implode(' ', $this->join);
            $sql .= ' HAVING ';
            foreach ($this->where as $column => $condition) {
                $sql .= "$column = $condition";
                unset($this->where[$column]);
                $symbol = '';
                if (count($this->where) > 0) {
                    $symbol = ', ';
                }
                $sql .= $symbol;
            }
        } else if (!empty($this->join) && empty($this->where)) {
            $sql .= ' ' . implode(' ', $this->join);
        } else if (empty($this->join) && !empty($this->where)) {
            $sql .= ' WHERE ' . implode(', ', $this->where);
        }

        if (!empty($this->limit)) {
            $sql .= ' LIMIT ' . $this->limit['limit'] . ' OFFSET ' . $this->limit['offset'];
        }



        $db = Db::getInstance();
        $db->sql($sql);
        return $db->fetchAll();
    }
}
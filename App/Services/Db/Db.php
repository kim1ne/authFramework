<?php

namespace App\Services\Db;

class Db
{
    private $db;
    private $sql;

    public function __construct()
    {
        $this->db = new \PDO('mysql:host=localhost;dbname=redis', 'root', '');
    }

    public function sql(string $sql)
    {
        $this->sql = $sql;
    }

    public function fetchAll($parameters = []):array
    {
        $sth = $this->db->prepare($this->sql);
        $sth->execute($parameters);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetch($parameters = []):array
    {
        try {
            $sth = $this->db->prepare($this->sql);
            $sth->execute($parameters);
            return $sth->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception($this->db->errorInfo());
        }
    }

    public function getObject(array $parameters = [], string $className = 'stdClass')
    {
        try {
            $sth = $this->db->prepare($this->sql);
            $sth->execute($parameters);
            return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
        } catch (\Exception $e) {
            throw new \Exception($this->db->errorInfo());
        }
    }

    public function lastInsertId(): int
    {
        return $this->db->lastInsertId();
    }

    public function execute($parameters = [])
    {
        $sth = $this->db->prepare($this->sql);
        $sth->execute($parameters);
        return $this;
    }
}
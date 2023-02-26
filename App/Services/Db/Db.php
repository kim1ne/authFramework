<?php

namespace App\Services\Db;

class Db
{
    private \PDO $db;
    private string $sql;
    private static $instance;

    private function __construct()
    {
        $this->db = new \PDO('mysql:host=localhost;dbname=redis', 'root', '');
    }

    public static function getInstance()
    {
        if (self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function sql(string $sql)
    {
        $this->sql = $sql;
        return $this;
    }

    public function fetchAll($parameters = []): bool|array
    {
        try {
            $sth = $this->db->prepare($this->sql);
            $sth->execute($parameters);
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function fetch($parameters = []): bool|array
    {
        try {
            $sth = $this->db->prepare($this->sql);
            $sth->execute($parameters);
            return $sth->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getObject(array $parameters = [], string $className = 'stdClass')
    {
        try {
            $sth = $this->db->prepare($this->sql);
            $sth->execute($parameters);
            return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function execute(array $parameters = []): bool
    {
        try {
            $sth = $this->db->prepare($this->sql);
            $sth->execute($parameters);
            return true;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }

    }

    public function lastInsertId(): int
    {
        return $this->db->lastInsertId();
    }
}
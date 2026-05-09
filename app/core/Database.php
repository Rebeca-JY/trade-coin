<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private $conn;
    private static $instance;

    public static function getInstance($host = 'localhost', $user = 'root', $pass = '', $db = 'tradecoin')
    {
        if (self::$instance === null) {
            self::$instance = new self($host, $user, $pass, $db);
        }
        return self::$instance;
    }

    /**
     * PDO mentah — dipakai model yang query langsung (mis. UserPoint).
     */
    public function getPdo(): PDO
    {
        return $this->conn;
    }

    public static function getConnection(): PDO
    {
        return self::getInstance()->getPdo();
    }

    public function __construct($host, $user, $pass, $db)
    {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        $this->conn = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public function select($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectOne($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($table, $data)
    {
        $keys = implode(',', array_keys($data));
        $values = implode(',', array_fill(0, count($data), '?'));

        $stmt = $this->conn->prepare("INSERT INTO $table ($keys) VALUES ($values)");
        return $stmt->execute(array_values($data));
    }

    public function update($table, $data, $where)
    {
        $set = implode('=?, ', array_keys($data)) . '=?';
        $cond = implode('=? AND ', array_keys($where)) . '=?';

        $stmt = $this->conn->prepare("UPDATE $table SET $set WHERE $cond");
        return $stmt->execute(array_merge(array_values($data), array_values($where)));
    }

    public function delete($table, $where)
    {
        $cond = implode('=? AND ', array_keys($where)) . '=?';

        $stmt = $this->conn->prepare("DELETE FROM $table WHERE $cond");
        return $stmt->execute(array_values($where));
    }
}
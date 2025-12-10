<?php


// system/core/Model.php
require_once '../../app/config/database.php';

class Model {
    protected PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function all(string $table): array {
        $stmt = $this->query("SELECT * FROM {$table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function find(string $table, string $column, $value): ?array {
        $stmt = $this->query("SELECT * FROM {$table} WHERE {$column} = :value LIMIT 1", ['value'=>$value]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}




<?php
// app/models/Usuario.php
require_once '../../system/core/Model.php';

class Usuario extends Model {
    protected string $table = 'usuarios';

    public function findByUsername(string $username): ?array {
        return $this->find($this->table, 'username', $username);
    }

    public function updateLastLogin(int $id_usuario): bool {
        $sql = "UPDATE {$this->table} SET ultimo_login = NOW() WHERE id_usuario = :id";
        $stmt = $this->query($sql, ['id' => $id_usuario]);
        return $stmt->rowCount() > 0;
    }
}

<?php
require_once '../config/conexion.php';
header('Content-Type: application/json');

try {
    // Verificar si ya existen roles
    $stmt = $pdo->query("SELECT COUNT(*) FROM roles");
    $total = $stmt->fetchColumn();

    if ($total > 0) {
        // Obtener el ID del rol Administrador
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE nombre = ?");
    $stmt->execute(['Administrador']);
    $rol_admin_id = $stmt->fetchColumn();

        echo json_encode([
            'status' => false,
            'message' => 'Los roles ya han sido creados. Este paso solo se ejecuta una vez.',
            'data' => $rol_admin_id
       
        ]);
        exit;
    }

    // Insertar roles iniciales
    $sql = "INSERT INTO roles (nombre) VALUES
        ('Administrador'),
        ('Vendedor'),
        ('Diseñador'),
        ('Operario')";
    $pdo->exec($sql);

    // Obtener el ID del rol Administrador
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE nombre = ?");
    $stmt->execute(['Administrador']);
    $rol_admin_id = $stmt->fetchColumn();

    echo json_encode([
        'status' => true,
        'message' => 'Roles iniciales creados correctamente.',
        'data' => $rol_admin_id
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => false,
        'message' => 'Error al crear los roles: ' . $e->getMessage()
    ]);
}

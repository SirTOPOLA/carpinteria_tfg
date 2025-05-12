<?php
require_once("../config/conexion.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents('php://input'), true);

// Validar campos obligatorios
if (empty($data['nombre']) || empty($data['apellido']) || empty($data['codigo'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Nombre, apellido y código son obligatorios']);
    exit;
}

$id = isset($data['id']) && is_numeric($data['id']) ? (int)$data['id'] : 0;

try {
    if ($id > 0) {
        // UPDATE
        $sql = "UPDATE empleados SET nombre = ?, apellido = ?, codigo = ?, email = ?, telefono = ?, direccion = ?, horario_trabajo = ?, salario = ?, fecha_ingreso = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['nombre'], $data['apellido'], $data['codigo'],
            $data['email'], $data['telefono'], $data['direccion'],
            $data['horario_trabajo'], $data['salario'], $data['fecha_ingreso'],
            $id
        ]);
    } else {
        // INSERT
        $sql = "INSERT INTO empleados (nombre, apellido, codigo, email, telefono, direccion, horario_trabajo, salario, fecha_ingreso)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['nombre'], $data['apellido'], $data['codigo'],
            $data['email'], $data['telefono'], $data['direccion'],
            $data['horario_trabajo'], $data['salario'], $data['fecha_ingreso']
        ]);
    }

    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'mensaje' => $e->getMessage()]);
}

<?php
header('Content-Type: application/json');
require '../config/conexion.php';

try {
    if (
        empty($_POST['pedido_id']) ||
        empty($_POST['responsable_id']) ||
        empty($_POST['fecha_inicio']) ||
        empty($_POST['fecha_fin'])  
       
    ) {
        throw new Exception('Todos los campos obligatorios deben estar completos.');
    }

    $pedido_id = $_POST['pedido_id'];
    $responsable_id = $_POST['responsable_id'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $estado_nombre = 'pendiente'; // Por defecto será "pendiente"

    // Buscar el ID del estado correspondiente al nombre y entidad
    $stmtEstado = $pdo->prepare("SELECT id FROM estados WHERE nombre = ? AND entidad = 'produccion' LIMIT 1");
    $stmtEstado->execute([$estado_nombre]);
    $estado = $stmtEstado->fetch(PDO::FETCH_ASSOC);

    if (!$estado) {
        throw new Exception('Estado no válido para producción.');
    }

    $estado_id = $estado['id'];

    // Insertar producción
    $stmt = $pdo->prepare("INSERT INTO producciones (solicitud_id, responsable_id, fecha_inicio, fecha_fin, estado_id)
                           VALUES (?, ?, ?, ?, ?)");

    $stmt->execute([
        $pedido_id,
        $responsable_id,
        $fecha_inicio,
        $fecha_fin,
        $estado_id
    ]);

    echo json_encode(['success' => true, 'message' => 'Producción registrada correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

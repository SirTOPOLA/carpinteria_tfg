<?php

require '../config/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idPedido = intval($_POST['id']);
    $estadoTexto = trim($_POST['estado']);

    try {
        $pdo->beginTransaction();

        $id = $_POST['id'];
        $estado = $_POST['estado'];
        $monto = isset($_POST['monto_pagado']) ? floatval($_POST['monto_pagado']) : null;

        if ($estado === 'aprobado') {
            if ($monto === null || $monto <= 0) {
                echo json_encode(['success' => false, 'message' => 'Monto inválido.']);
                exit;
            }

            // Actualiza estado y guarda adelanto
            $stmt = $pdo->prepare("UPDATE pedidos SET estado_id = (SELECT id FROM estados WHERE nombre = :estado), adelanto = :monto WHERE id = :id");
            $stmt->execute([':estado' => $estado, ':monto' => $monto, ':id' => $id]);
        } else {
            // Solo cambia estado
            $stmt = $pdo->prepare("UPDATE pedidos SET estado_id = (SELECT id FROM estados WHERE nombre = :estado) WHERE id = :id");
            $stmt->execute([':estado' => $estado, ':id' => $id]);
        }



        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'estado actualizado']);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
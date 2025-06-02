<?php

require_once '../config/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

 

try {
    $compra_id = (int) ($_POST['id'] ?? 0);

    if ($compra_id <= 0) {
        throw new Exception("ID de compra no válido.");
    }

    $pdo->beginTransaction();

    // 1. Obtener detalles para revertir el stock
    $stmt = $pdo->prepare("SELECT material_id, cantidad FROM detalles_compra WHERE compra_id = ?");
    $stmt->execute([$compra_id]);
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($detalles as $item) {
        $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual - ? WHERE id = ?")
            ->execute([$item['cantidad'], $item['material_id']]);
    }

    // 2. Eliminar los detalles de compra
    $pdo->prepare("DELETE FROM detalles_compra WHERE compra_id = ?")
        ->execute([$compra_id]);

    // 3. Eliminar la compra
    $pdo->prepare("DELETE FROM compras WHERE id = ?")
        ->execute([$compra_id]);

    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar la compra: ' . $e->getMessage()
    ]);
}

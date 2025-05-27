<?php
require_once '../config/conexion.php';
header('Content-Type: application/json');

try {
    if (empty($_POST['id'])) {
        throw new Exception('ID de venta no proporcionado.');
    }

    $ventaId = intval($_POST['id']);

    // Iniciar transacción
    $pdo->beginTransaction();

    // Eliminar detalles de la venta
    $stmtDetalles = $pdo->prepare("DELETE FROM detalles_venta WHERE venta_id = ?");
    $stmtDetalles->execute([$ventaId]);

    // Eliminar la venta
    $stmtVenta = $pdo->prepare("DELETE FROM ventas WHERE id = ?");
    $stmtVenta->execute([$ventaId]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Venta eliminada correctamente.'
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar venta: ' . $e->getMessage()
    ]);
}
?>

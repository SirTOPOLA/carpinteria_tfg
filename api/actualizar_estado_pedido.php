<?php
  
require '../config/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
$idPedido = intval($_POST['id']);
$estado_texto = trim($_POST['estado']);

try {
    $pdo->beginTransaction();

    // Obtener el pedido
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
    $stmt->execute([$idPedido]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido || $pedido['estado'] !== 'cotizado') {
        throw new Exception("Pedido no válido o ya aprobado.");
    }

    // Buscar el ID del estado en tabla estados
    $stmtEstado = $pdo->prepare("SELECT id FROM estados WHERE nombre = ? AND entidad = 'pedido' LIMIT 1");
    $stmtEstado->execute([$estado_texto]);
    $idEstado = $stmtEstado->fetchColumn();
    if (!$idEstado) {
        echo json_encode(['status' => false, 'message' => 'Estado no encontrado']);
        exit;
    }

    // Cambiar estado del pedido
    $pdo->prepare("UPDATE pedidos SET estado_id = ? WHERE id = ?")->execute([$idEstado,$idPedido]);

    // Insertar venta
    $stmt = $pdo->prepare("INSERT INTO ventas (cliente_id, pedido_id, total, fecha_venta, estado) VALUES (?, ?, ?, NOW(), 'pendiente')");
    $stmt->execute([$pedido['cliente_id'], $idPedido, $pedido['estimacion_total']]);
    $idVenta = $pdo->lastInsertId();

    $pdo->commit();

    echo json_encode(['success' => true, 'venta_id' => $idVenta]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
}

}
?>

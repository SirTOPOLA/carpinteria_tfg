<?php
function totalVentas(PDO $pdo) {
    $stmt = $pdo->query("SELECT SUM(total) as total FROM ventas");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function contarProduccionesActivas(PDO $pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM producciones WHERE estado_id != 4");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function contarStockBajo(PDO $pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM materiales WHERE stock_actual <= stock_minimo");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function tareasOperario(PDO $pdo, $usuario_id) {
    $stmt = $pdo->prepare("
        SELECT t.descripcion, e.nombre AS estado
        FROM tareas_produccion t
        JOIN estados e ON t.estado_id = e.id
        WHERE t.responsable_id = ?
        ORDER BY fecha_inicio DESC
        LIMIT 5
    ");
    $stmt->execute([$usuario_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function produccionesAsignadas(PDO $pdo, $usuario_id) {
    $stmt = $pdo->prepare("
        SELECT p.id, e.nombre AS estado
        FROM producciones p
        JOIN estados e ON p.estado_id = e.id
        WHERE p.responsable_id = ?
        ORDER BY p.fecha_inicio DESC
        LIMIT 5
    ");
    $stmt->execute([$usuario_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function pedidosCliente(PDO $pdo, $cliente_id) {
    $stmt = $pdo->prepare("
        SELECT p.proyecto, e.nombre as estado, p.fecha_entrega
        FROM pedidos p
        JOIN estados e ON p.estado_id = e.id
        WHERE p.cliente_id = ?
        ORDER BY p.fecha_solicitud DESC
        LIMIT 10
    ");
    $stmt->execute([$cliente_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function totalCompras($pdo) {
    $stmt = $pdo->query("SELECT SUM(total) FROM compras");
    return $stmt->fetchColumn() ?: 0;
  }
  
  function contarFacturasPendientes($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM facturas WHERE saldo_pendiente > 0");
    return $stmt->fetchColumn();
  }
  
  function totalGananciaEstimada($pdo) {
    $ventas = totalVentas($pdo);
    $compras = totalCompras($pdo);
    return $ventas - $compras;
  }
  
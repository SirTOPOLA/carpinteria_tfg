<?php

require_once '../config/conexion.php';

header('Content-Type: application/json');

$producto_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($producto_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID no válido']);
    exit;
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // 1. Obtener rutas de las imágenes asociadas
    $sqlSelectImg = "SELECT ruta_imagen FROM imagenes_producto WHERE producto_id = ?";
    $stmtSelectImg = $pdo->prepare($sqlSelectImg);
    $stmtSelectImg->execute([$producto_id]);
    $imagenes = $stmtSelectImg->fetchAll(PDO::FETCH_ASSOC);

    // 2. Eliminar archivos físicos del servidor
    foreach ($imagenes as $img) {
        $ruta =  $img['ruta_imagen']; // Ajusta según dónde se guarda la imagen
        if (file_exists($ruta)) {
            unlink($ruta);
        }
    }

    // 3. Eliminar registros de imagen en BD
    $sqlImg = "DELETE FROM imagenes_producto WHERE producto_id = ?";
    $stmtImg = $pdo->prepare($sqlImg);
    $stmtImg->execute([$producto_id]);

    // 4. Eliminar el producto
    $sqlProducto = "DELETE FROM productos WHERE id = ?";
    $stmtProducto = $pdo->prepare($sqlProducto);
    $stmtProducto->execute([$producto_id]);

    if ($stmtProducto->rowCount() > 0) {
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Producto e imágenes eliminados correctamente']);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'No se encontró el producto o ya fue eliminado']);
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
}

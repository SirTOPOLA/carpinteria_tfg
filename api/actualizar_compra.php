<?php

require_once '../config/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

 

try {
    $pdo->beginTransaction();
    $total = $_POST['total'] ?? 0;

    $compra_id = (int) ($_POST['id_compra'] ?? 0);
    $proveedor_id = (int) ($_POST['proveedor_id'] ?? 0);
    $fecha = $_POST['fecha'] ?? null;

    if (!$compra_id || !$proveedor_id || !$fecha) {
        throw new Exception('Datos de compra incompletos.');
    }

    // 1. Recuperar detalles anteriores
    $stmt = $pdo->prepare("SELECT material_id, cantidad FROM detalles_compra WHERE compra_id = ?");
    $stmt->execute([$compra_id]);
    $anteriores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Revertir stock anterior
    foreach ($anteriores as $item) {
        $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual - ? WHERE id = ?")
            ->execute([$item['cantidad'], $item['material_id']]);
    }

    // 3. Eliminar detalles anteriores
    $pdo->prepare("DELETE FROM detalles_compra WHERE compra_id = ?")
        ->execute([$compra_id]);

    // 4. Actualizar encabezado de compra
    $pdo->prepare("UPDATE compras SET proveedor_id = ?, total = ?, fecha = ? WHERE id = ?")
        ->execute([$proveedor_id, $total, $fecha, $compra_id]);

    // 5. Insertar materiales editados existentes
    if (isset($_POST['detalle_id'], $_POST['material_id'], $_POST['cantidad'], $_POST['precio_unitario'])) {
        $detalle_ids = $_POST['detalle_id'];
        $material_ids = $_POST['material_id'];
        $cantidades = $_POST['cantidad'];
        $precios = $_POST['precio_unitario'];

        for ($i = 0; $i < count($material_ids); $i++) {
            $mid = (int) $material_ids[$i];
            $cant = (float) $cantidades[$i];
            $precio = (float) $precios[$i];

            $pdo->prepare("INSERT INTO detalles_compra (compra_id, material_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)")
                ->execute([$compra_id, $mid, $cant, $precio]);

            // Sumar al stock
            $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual + ? WHERE id = ?")
                ->execute([$cant, $mid]);
        }
    }

    // 6. Insertar materiales nuevos agregados dinámicamente
    if (isset($_POST['material_ids'], $_POST['cantidades'], $_POST['precios'])) {
        $nuevos_ids = $_POST['material_ids'];
        $nuevas_cant = $_POST['cantidades'];
        $nuevos_prec = $_POST['precios'];

        for ($i = 0; $i < count($nuevos_ids); $i++) {
            $mid = (int) $nuevos_ids[$i];
            $cant = (float) $nuevas_cant[$i];
            $precio = (float) $nuevos_prec[$i];

            $pdo->prepare("INSERT INTO detalles_compra (compra_id, material_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)")
                ->execute([$compra_id, $mid, $cant, $precio]);

            $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual + ? WHERE id = ?")
                ->execute([$cant, $mid]);
        }
    }

    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar la compra: ' . $e->getMessage()
    ]);
}

<?php

include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Método no permitido']);
  exit;
}

try {
  if (empty($_POST['proveedor_id']) || empty($_POST['fecha']) || empty($_POST['material_id']) || empty($_POST['cantidad']) || empty($_POST['precio_unitario'])) {
    throw new Exception('Datos incompletos.');
  }

  $compra_id = isset($_POST['id_compra']) ? intval($_POST['id_compra']) : null;
  $proveedor_id = intval($_POST['proveedor_id']);
  $fecha = $_POST['fecha'];
  $codigo = trim($_POST['codigo'] ?? '');
  $total = floatval($_POST['total']);

  $materiales = $_POST['material_id'];
  $cantidades = $_POST['cantidad'];
  $precios = $_POST['precio_unitario'];

  // Si no se envía código, generarlo
  if (empty($codigo)) {
    $fechaHoy = date('Ymd');
    $stmt = $pdo->prepare("SELECT COUNT(*) + 1 FROM compras WHERE DATE(fecha) = CURDATE()");
    $stmt->execute();
    $orden = str_pad($stmt->fetchColumn(), 4, '0', STR_PAD_LEFT);
    $codigo = "#SIXBOKU-{$fechaHoy}-{$orden}";
  }

  $pdo->beginTransaction();


  // Nueva compra
  $sql = "INSERT INTO compras (proveedor_id, fecha, total, codigo) VALUES (?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$proveedor_id, $fecha, $total, $codigo]);
  $compra_id = $pdo->lastInsertId();


  // Insertar nuevos detalles y actualizar stock
  $sql_det = "INSERT INTO detalles_compra (compra_id, material_id, cantidad, precio_unitario, stock) VALUES (?, ?, ?, ?, ?)";
  $stmt_det = $pdo->prepare($sql_det);

  for ($i = 0; $i < count($materiales); $i++) {
    $material_id = intval($materiales[$i]);
    $cantidad = floatval($cantidades[$i]);
    $precio = floatval($precios[$i]);

    // Insertar detalle
    $stmt_det->execute([$compra_id, $material_id, $cantidad, $precio, $cantidad]);

    // Actualizar stock en materiales
    $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual + ? WHERE id = ?")
      ->execute([$cantidad, $material_id]);
  }

  $pdo->commit();

  echo json_encode([
    'success' => true,
    'message' => $compra_id ? 'Compra actualizada correctamente.' : 'Compra registrada exitosamente.',
    'codigo_generado' => $codigo
  ]);
} catch (Exception $e) {
  $pdo->rollBack();
  echo json_encode([
    'success' => true,
    'message' => 'Error: ' . $e->getMessage()
  ]);
}

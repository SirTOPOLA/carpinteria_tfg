<?php
require_once '../config/conexion.php';

header('Content-Type: application/json');

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Recoger datos
$tipo = $_POST['tipo'] ?? [];
$materiales = $_POST['material_id'] ?? [];
$cantidades = $_POST['cantidad'] ?? [];
$motivos = $_POST['motivo'] ?? [];
$produccion_id = $_POST['produccion_id'] ?? null;

// Validación básica
if (!in_array($tipo, ['entrada', 'salida'])) {
    echo json_encode(['success' => false, 'message' => 'Tipo de movimiento inválido.']);
    exit;
}

if (!is_array($materiales) || count($materiales) === 0 || count($materiales) !== count($cantidades)) {
    echo json_encode(['success' => false, 'message' => 'Datos de materiales o cantidades inválidos.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Preparar sentencias
    $stmtMaterial = $pdo->prepare("SELECT stock_actual, stock_minimo FROM materiales WHERE id = ?");
    $stmtInsertMov = $pdo->prepare("INSERT INTO movimientos_material 
        (material_id, tipo_movimiento, cantidad, motivo, produccion_id) 
        VALUES (?, ?, ?, ?, ?)");
    $stmtUpdateStock = $pdo->prepare("UPDATE materiales SET stock_actual = ? WHERE id = ?");

    foreach ($materiales as $i => $material_id) {
        $material_id = (int) $material_id;
        $cantidad = (int) $cantidades[$i];
        $motivo = trim($motivos[$i] ?? '');

        if ($cantidad <= 0) {
            throw new Exception("La cantidad debe ser mayor a 0 para el material ID $material_id.");
        }

        // Obtener stock actual
        $stmtMaterial->execute([$material_id]);
        $material = $stmtMaterial->fetch(PDO::FETCH_ASSOC);

        if (!$material) {
            throw new Exception("Material con ID $material_id no encontrado.");
        }

        $stock_actual = (int) $material['stock_actual'];
        $stock_minimo = (int) $material['stock_minimo'];

        if ($tipo === 'salida') {
            $nuevo_stock = $stock_actual - $cantidad;

            if ($nuevo_stock < 0) {
                throw new Exception("El material ID $material_id tiene stock insuficiente. Stock actual: $stock_actual.");
            }

            if ($nuevo_stock < $stock_minimo) {
                throw new Exception("La salida del material ID $material_id dejaría el stock por debajo del mínimo permitido ($stock_minimo).");
            }
        } else {
            $nuevo_stock = $stock_actual + $cantidad;
        }

        // Insertar movimiento
        $stmtInsertMov->execute([
            $material_id,
            $tipo,
            $cantidad,
            $motivo,
            $produccion_id ?: null
        ]);

        // Actualizar stock
        $stmtUpdateStock->execute([$nuevo_stock, $material_id]);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Movimiento registrado correctamente.']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

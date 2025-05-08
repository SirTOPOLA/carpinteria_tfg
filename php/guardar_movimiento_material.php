<?php
require_once '../includes/conexion.php';
session_start();

// Respuesta JSON en caso de ser usado vía fetch o para debug
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Verifica que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método no permitido.';
    echo json_encode($response);
    exit;
}

// Función para sanear entradas
function clean_input($data) {
    return htmlspecialchars(trim($data));
}

// Sanitización y validación de entrada
$material_id = isset($_POST['material_id']) ? (int)$_POST['material_id'] : 0;
$tipo = isset($_POST['tipo']) ? clean_input($_POST['tipo']) : '';
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;
$produccion_id = isset($_POST['produccion_id']) ? (int)$_POST['produccion_id'] : 0;
$observaciones = isset($_POST['observaciones']) ? clean_input($_POST['observaciones']) : '';

if (!$material_id || !$tipo || !$cantidad || !$produccion_id) {
    $response['message'] = 'Faltan datos obligatorios.';
    echo json_encode($response);
    exit;
}

if (!in_array($tipo, ['entrada', 'salida'])) {
    $response['message'] = 'Tipo de movimiento inválido.';
    echo json_encode($response);
    exit;
}

try {
    $pdo->beginTransaction();

    // Validar existencia del material y producción
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM materiales WHERE id = ?");
    $stmt->execute([$material_id]);
    if ($stmt->fetchColumn() == 0) {
        throw new Exception('Material no encontrado.');
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM producciones WHERE id = ?");
    $stmt->execute([$produccion_id]);
    if ($stmt->fetchColumn() == 0) {
        throw new Exception('Producción no encontrada.');
    }

    // Validar stock si es salida
    if ($tipo === 'salida') {
        $stmt = $pdo->prepare("SELECT SUM(cantidad) FROM detalles_compra WHERE material_id = ?");
        $stmt->execute([$material_id]);
        $stock_disponible = (int)$stmt->fetchColumn();

        if ($cantidad > $stock_disponible) {
            throw new Exception("Stock insuficiente. Disponible: $stock_disponible unidades.");
        }

        // Restar del stock desde detalles_compra según FIFO
        $stmt = $pdo->prepare("
            SELECT id, cantidad 
            FROM detalles_compra 
            WHERE material_id = ? AND cantidad > 0 
            ORDER BY fecha_compra ASC
        ");
        $stmt->execute([$material_id]);
        $restante = $cantidad;

        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($restante <= 0) break;
            $a_deducir = min($fila['cantidad'], $restante);

            $update = $pdo->prepare("UPDATE detalles_compra SET cantidad = cantidad - ? WHERE id = ?");
            $update->execute([$a_deducir, $fila['id']]);

            $restante -= $a_deducir;
        }
    }

    // Si es entrada, actualizar stock_actual
    if ($tipo === 'entrada') {
        $update = $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual + ? WHERE id = ?");
        $update->execute([$cantidad, $material_id]);
    } elseif ($tipo === 'salida') {
        // También actualizar stock_actual
        $update = $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual - ? WHERE id = ?");
        $update->execute([$cantidad, $material_id]);
    }

    // Insertar en la tabla de movimientos
    $insert = $pdo->prepare("
        INSERT INTO movimiento_material (material_id, tipo, cantidad, produccion_id, observaciones, fecha_movimiento)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $insert->execute([$material_id, $tipo, $cantidad, $produccion_id, $observaciones]);

    $pdo->commit();

    $response['success'] = true;
    $response['message'] = 'Movimiento registrado exitosamente.';

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Error al guardar movimiento: " . $e->getMessage());
    $response['message'] = "Error al guardar el movimiento: " . $e->getMessage();
}

echo json_encode($response);

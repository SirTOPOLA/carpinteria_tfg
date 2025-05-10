<?php
require_once '../includes/conexion.php';
session_start();

// Respuesta JSON en caso de ser usado vía fetch o para debug
//header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Verifica que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método no permitido.';
    //echo json_encode($response);
    header('location: ../dashboard/registrar_movimientos_matearial.php');
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
//$fecha = $_POST['fecha'] ? clean_input($_POST['fecha']) : '';

if (!$material_id || !$tipo || !$cantidad || !$produccion_id) {
    $response['message'] = 'Faltan datos obligatorios.';
    //echo json_encode($response);
    header('location: ../dashboard/registrar_movimientos_matearial.php');
    exit;
}

if (!in_array($tipo, ['entrada', 'salida'])) {
    $response['message'] = 'Tipo de movimiento inválido.';
   // echo json_encode($response);
    header('location: ../dashboard/registrar_movimientos_matearial.php');
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
        $stmt = $pdo->prepare("SELECT stock_actual, stock_minimo FROM materiales WHERE id = ?");
        $stmt->execute([$material_id]);
        $stock_disponible = $stmt->fetch();

        if ($cantidad > (int)$stock_disponible['stock_actual'] && $cantidad < (int)$stock_disponible['stock_minimo'] ) {
            throw new Exception("Stock insuficiente. Disponible: $stock_disponible unidades.");
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
        INSERT INTO movimientos_material (material_id, tipo_movimiento, cantidad, produccion_id, motivo, fecha)
        VALUES (?, ?, ?, ?, ?, now())
        ");
        $insert->execute([$material_id, $tipo, $cantidad, $produccion_id, $observaciones ]);
       
    $pdo->commit();

    $response['success'] = true;
    $response['message'] = 'Movimiento registrado exitosamente.';
    
   // header('location: ../dashboard/movimientos_material.php');
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Error al guardar movimiento: " . $e->getMessage());
    $response['message'] = "Error al guardar el movimiento: " . $e->getMessage();
    //header('location: ../dashboard/registrar_movimientos_material.php');
}
//echo json_encode($response);

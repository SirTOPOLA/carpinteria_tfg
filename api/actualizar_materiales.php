<?php
require '../config/conexion.php';
header('Content-Type: application/json');

function limpiar($cadena)
{
    return htmlspecialchars(strip_tags(trim($cadena)), ENT_QUOTES, 'UTF-8');
}



$id = intval(limpiar($_POST['material_id']));
$nombre = limpiar($_POST['nombre']);
$descripcion = limpiar($_POST['descripcion'] ?? '');
$unidad_medida = limpiar($_POST['unidad_medida']);
$stock_minimo = limpiar($_POST['stock_minimo']);

// Validaciones
$errores = [];

// Validar nombre
if (empty($nombre)) {
    $errores[] = 'El nombre del material es obligatorio.';
} elseif (strlen($nombre) < 3) {
    $errores[] = 'El nombre debe tener al menos 3 caracteres.';
}

// Validar unidad de medida
if (empty($unidad_medida)) {
    $errores[] = 'La unidad de medida es obligatoria.';
}

// Validar stock mínimo
if (empty($stock_minimo)) {
    $errores[] = 'El stock mínimo es obligatorio.';
} elseif (!ctype_digit($stock_minimo) || intval($stock_minimo) <= 0) {
    $errores[] = 'El stock mínimo debe ser un número entero positivo y mayor a cero.';
}

// Si hay errores, devolverlos
if (!empty($errores)) {
    echo json_encode(['success' => false, 'message' => $errores]);
    exit;
}

// Si pasa validación, actualizar
try {
    $stmt = $pdo->prepare("UPDATE materiales SET nombre = ?, descripcion = ?, unidad_medida = ?, stock_minimo = ? WHERE id = ?");
    $stmt->execute([$nombre, $descripcion, $unidad_medida, $stock_minimo, $id]);
    echo json_encode(['success' => true, 'message' => 'Material actualizado correctamente.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el material. ' . $e->getMessage()]);
}

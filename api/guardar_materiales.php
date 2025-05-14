<?php
header('Content-Type: application/json');
require_once '../config/conexion.php';

// Función para limpiar entradas
function limpiar($cadena)
{
    return htmlspecialchars(strip_tags(trim($cadena)), ENT_QUOTES, 'UTF-8');
}

try {
    // Sanitizar y validar datos
    $nombre = limpiar($_POST['nombre'] ?? '');
    $unidad_medida = limpiar($_POST['unidad_medida'] ?? '');
    $stock_minimo = limpiar($_POST['stock_minimo'] ?? '');
    $descripcion = limpiar($_POST['descripcion'] ?? '');


    // Validaciones robustas
    $errores = [];

    // Validar nombre
    if ($nombre === '') {
        $errores[] = 'El nombre del material es obligatorio.';
    } elseif (strlen($nombre) < 3) {
        $errores[] = 'El nombre debe tener al menos 3 caracteres.';
    } else {
        // Verificar duplicado
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM materiales WHERE nombre = ?");
        $stmt->execute([$nombre]);
        if ($stmt->fetchColumn() > 0) {
            $errores[] = 'Ya existe un material con ese nombre.';
        }
    }

    // Validar unidad de medida
    if ($unidad_medida === '') {
        $errores[] = 'La unidad de medida es obligatoria.';
    }

    // Validar stock mínimo
    if ($stock_minimo === '') {
        $errores[] = 'El stock mínimo es obligatorio.';
    } elseif (!ctype_digit($stock_minimo) || intval($stock_minimo) <= 0) {
        $errores[] = 'El stock mínimo debe ser un número entero positivo y superior a cero.';
    }

    // Si hay errores, se devuelven
    if (!empty($errores)) {
        echo json_encode(['success' => false, 'message' => $errores]);
        exit;
    }

    // Preparar e insertar datos con seguridad
    $sql = "INSERT INTO materiales (nombre, unidad_medida, stock_minimo, descripcion)
             VALUES (:nombre, :unidad_medida, :stock_minimo, :descripcion)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':unidad_medida' => $unidad_medida,
        ':stock_minimo' => intval($stock_minimo),
        ':descripcion' => $descripcion !== '' ? $descripcion : null
    ]);

    echo json_encode(['success' => true, 'message' => 'Material registrado correctamente.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
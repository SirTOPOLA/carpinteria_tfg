<?php
require_once("../config/conexion.php");

$errores = [];
$respuesta = ['success' => false, 'mensaje' => ''];

$entidades = ['produccion', 'proyecto', 'solicitud', 'venta', 'factura'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $entidad = trim($_POST['entidad'] ?? '');

    if (empty($nombre)) {
        $errores[] = "El nombre del estado es obligatorio.";
    } elseif (strlen($nombre) < 3 || strlen($nombre) > 100) {
        $errores[] = "El nombre debe tener entre 3 y 100 caracteres.";
    }

    if (empty($entidad) || !in_array($entidad, $entidades)) {
        $errores[] = "Selecciona una entidad válida.";
    }

    if (empty($errores)) {
        try {
            $verifica = $pdo->prepare("SELECT COUNT(*) FROM estados WHERE nombre = :nombre AND entidad = :entidad");
            $verifica->execute([
                ':nombre' => $nombre,
                ':entidad' => $entidad
            ]);

            if ($verifica->fetchColumn() > 0) {
                $respuesta['mensaje'] = "Ya existe un estado con ese nombre para esa entidad.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO estados (nombre, entidad) VALUES (:nombre, :entidad)");
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':entidad' => $entidad
                ]);
                $respuesta['success'] = true;
                $respuesta['mensaje'] = "Estado registrado correctamente.";
            }
        } catch (PDOException $e) {
            $respuesta['mensaje'] = "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        $respuesta['mensaje'] = implode('<br>', $errores);
    }
}

echo json_encode($respuesta);
exit;

<?php

header('Content-Type: application/json');
require_once '../config/conexion.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produccion_id = $_POST['produccion_id'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $porcentaje = isset($_POST['porcentaje']) ? intval($_POST['porcentaje']) : null;

    // Validaciones básicas
    if (!$produccion_id || !$descripcion || $porcentaje === null) {
        $response['error'] = 'Faltan campos obligatorios.';
        echo json_encode($response);
        exit;
    }

    // Validar rango del porcentaje
    if ($porcentaje <= 0 || $porcentaje > 100) {
        $response['error'] = 'El porcentaje debe estar entre 1 y 100.';
        echo json_encode($response);
        exit;
    }

    // Verificar avances previos
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total_avances, COALESCE(SUM(porcentaje), 0) AS suma_porcentaje 
                           FROM avances_produccion 
                           WHERE produccion_id = ?");
    $stmt->execute([$produccion_id]);
    $avance = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_avances = intval($avance['total_avances']);
    $suma_porcentaje = intval($avance['suma_porcentaje']);

    if ($total_avances >= 4) {
        $response['error'] = 'Ya se han registrado los 4 avances permitidos para este pedido.';
        echo json_encode($response);
        exit;
    }

    if (($suma_porcentaje + $porcentaje) > 100) {
        $response['error'] = "El porcentaje ingresado excede el avance restante disponible (" . (100 - $suma_porcentaje) . "%).";
        echo json_encode($response);
        exit;
    }

    // Procesar imagen si se envió
    $imagenPath = null;
    if (!empty($_FILES['imagen']['tmp_name']) && is_uploaded_file($_FILES['imagen']['tmp_name'])) {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['imagen']['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($mimeType, $allowedMimeTypes)) {
            $response['error'] = 'Formato de imagen no permitido.';
            echo json_encode($response);
            exit;
        }

        $uploadsDir = 'uploads/produccion/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        $baseName = basename($_FILES['imagen']['name']);
        $baseName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $baseName);
        $uniqueName = uniqid('img_', true) . '_' . $baseName;
        $ruta = $uploadsDir . $uniqueName;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
            $response['error'] = 'Error al mover el archivo subido.';
            echo json_encode($response);
            exit;
        }

        $imagenPath = $ruta;
    }

    // Registrar avance
    try {
        $stmt = $pdo->prepare("INSERT INTO avances_produccion (produccion_id, descripcion, imagen, porcentaje) 
                               VALUES (:produccion_id, :descripcion, :imagen, :porcentaje)");
        $stmt->bindParam(':produccion_id', $produccion_id, PDO::PARAM_INT);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagenPath, PDO::PARAM_STR);
        $stmt->bindParam(':porcentaje', $porcentaje, PDO::PARAM_INT);
        $stmt->execute();

        $response['success'] = true;
    } catch (PDOException $e) {
        $response['error'] = 'Error en la base de datos: ' . $e->getMessage();
    }
}

echo json_encode($response);

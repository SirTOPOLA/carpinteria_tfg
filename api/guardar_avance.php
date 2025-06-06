<?php

header('Content-Type: application/json');
require_once '../config/conexion.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produccion_id = $_POST['produccion_id'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;

    // Validaciones básicas
    if (!$produccion_id || !$descripcion) {
        $response['error'] = 'Faltan campos obligatorios.';
        echo json_encode($response);
        exit;
    }

    // Inicializar ruta de imagen
    $imagenPath = null;

    // Procesar imagen si se envió
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

        // Sanitizar nombre del archivo
        $baseName = basename($_FILES['imagen']['name']);
        $baseName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $baseName);
        $uniqueName = uniqid('img_', true) . '_' . $baseName;
        $ruta = $uploadsDir . $uniqueName;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
            $response['error'] = 'Error al mover el archivo subido.';
            echo json_encode($response);
            exit;
        }

        $imagenPath = $uploadsDir. $uniqueName;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO avances_produccion (produccion_id, descripcion, imagen) VALUES (:produccion_id, :descripcion, :imagen)");
        $stmt->bindParam(':produccion_id', $produccion_id, PDO::PARAM_INT);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagenPath, PDO::PARAM_STR);
        $stmt->execute();

        $response['success'] = true;
    } catch (PDOException $e) {
        $response['error'] = 'Error en la base de datos: ' . $e->getMessage();
    }
}

echo json_encode($response);

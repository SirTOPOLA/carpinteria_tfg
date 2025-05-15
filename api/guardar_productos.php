<?php
header('Content-Type: application/json');
require_once '../config/conexion.php'; // Asegúrate de tener la conexión PDO

$response = ['success' => false, 'message' => ''];

try {
    // -------------------------
    // Sanitizar y validar datos
    // -------------------------
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria_id = intval($_POST['categoria_id'] ?? 0);
    $precio_unitario = floatval($_POST['precio_unitario'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);

    if ($nombre === '' || $precio_unitario <= 0 ) {
        throw new Exception('Nombre, precio y categoría son obligatorios y deben ser válidos.');
    }

    if (!is_numeric($precio_unitario) || $precio_unitario <= 0) {
        throw new Exception('El precio unitario debe ser un número válido mayor que cero.');
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE nombre = ?");
    $stmt->execute([$nombre]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('Ya existe un producto con ese nombre.');
    }

    // -------------------------
    // Insertar producto
    // -------------------------
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio_unitario, stock)
                           VALUES (?, ?,  ?, ?)");
    $stmt->execute([$nombre, $descripcion,  $precio_unitario, $stock]);

    $producto_id = $pdo->lastInsertId();

    // -------------------------
    // Procesar imágenes
    // -------------------------
    if (!empty($_FILES['imagenes']['name'][0])) {
        $carpetaDestino = 'uploads/productos/';

        // Crear carpeta si no existe
        if (!is_dir($carpetaDestino)) {
            if (!mkdir($carpetaDestino, 0755, true)) {
                throw new Exception("No se pudo crear la carpeta '$carpetaDestino'.");
            }
        }

        // Verificar y corregir permisos si no es escribible
        if (!is_writable($carpetaDestino)) {
            // Intentar asignar permisos de escritura
            if (!chmod($carpetaDestino, 0755)) {
                throw new Exception("La carpeta '$carpetaDestino' no es escribible y no se pudieron asignar permisos.");
            }

            // Verificar nuevamente después de chmod
            if (!is_writable($carpetaDestino)) {
                throw new Exception("La carpeta '$carpetaDestino' sigue sin permisos de escritura después de intentar corregirlos.");
            }
        }


        foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmpName) {
            $nombreArchivo = $_FILES['imagenes']['name'][$i];
            $tamanoArchivo = $_FILES['imagenes']['size'][$i];
            $tipoArchivo = $_FILES['imagenes']['type'][$i];
            $errorArchivo = $_FILES['imagenes']['error'][$i];

            if ($errorArchivo !== UPLOAD_ERR_OK) {
                throw new Exception("Error al subir imagen '$nombreArchivo'.");
            }

            if ($tamanoArchivo > 2 * 1024 * 1024) {
                throw new Exception("La imagen '$nombreArchivo' excede los 2MB.");
            }

            $extensionesValidas = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
            if (!in_array($ext, $extensionesValidas)) {
                throw new Exception("La imagen '$nombreArchivo' tiene un formato no permitido.");
            }

            $nombreSeguro = uniqid('img_') . '.' . $ext;
            $rutaFinal = $carpetaDestino . $nombreSeguro;

            if (!move_uploaded_file($tmpName, $rutaFinal)) {
                throw new Exception("No se pudo guardar la imagen '$nombreArchivo'.");
            }

            $stmt = $pdo->prepare("INSERT INTO imagenes_producto (producto_id, ruta_imagen)
                                   VALUES (?, ?)");
            $stmt->execute([$producto_id, $rutaFinal]);
        }
    }

    $pdo->commit();
    $response['success'] = true;
    $response['message'] = 'Producto registrado correctamente.';
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

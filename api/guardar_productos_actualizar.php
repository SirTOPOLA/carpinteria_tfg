<?php

require '../config/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //funcion para singularizar
    function singularizar($palabra)
    {
        // Reglas simples para plural en español
        $reglas = [
            '/ces$/' => 'z',     // peces → pez
            '/es$/' => '',       // papeles → papel
            '/s$/' => '',        // árboles → árbol
        ];
        foreach ($reglas as $patron => $reemplazo) {
            if (preg_match($patron, $palabra)) {
                return preg_replace($patron, $reemplazo, $palabra);
            }
        }
        return $palabra;
    }

    // Función para normalizar texto
    function normalizarTexto($cadena)
    {
        $cadena = mb_strtolower(trim($cadena), 'UTF-8');
        $cadena = preg_replace('/[áàäâ]/u', 'a', $cadena);
        $cadena = preg_replace('/[éèëê]/u', 'e', $cadena);
        $cadena = preg_replace('/[íìïî]/u', 'i', $cadena);
        $cadena = preg_replace('/[óòöô]/u', 'o', $cadena);
        $cadena = preg_replace('/[úùüû]/u', 'u', $cadena);
        $cadena = preg_replace('/[^a-z0-9\s]/u', '', $cadena);
        $cadena = preg_replace('/[^a-z0-9\s]/', '', $cadena); // quita caracteres raros
        $palabras = preg_split('/\s+/', $cadena);
        // Singularizar y eliminar duplicados
        $reducido = array_unique(array_map('singularizar', $palabras));
        sort($reducido); // para orden consistente
        return implode(' ', $reducido); // cadena canónica
    }

    try {
        $response = ['success' => false, 'message' => ''];

        // -------------------------
        // Sanitizar y validar datos
        // -------------------------
        $producto_id = intval($_POST['producto_id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio_unitario = floatval($_POST['precio_unitario'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);

        if ($nombre === '' || $precio_unitario <= 0) {
            throw new Exception('Nombre y precio unitario son obligatorios y deben ser válidos.');
        }

        $nombre_normalizado = normalizarTexto($nombre);

        // Verificar duplicados (excepto en edición del mismo producto)
        $stmt = $pdo->prepare("SELECT id, nombre FROM productos WHERE id != ?");
        $stmt->execute([$producto_id]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($productos as $producto) {
            if (normalizarTexto($producto['nombre']) === $nombre_normalizado) {
                throw new Exception('Ya existe un producto con ese nombre.');
            }
        }

        $pdo->beginTransaction();

        // Inicializamos la variable para la ruta de imagen (en caso que no haya upload)
        $rutaImagen = null;

        // -------------------------
        // Procesar imagen (si existe)
        // -------------------------
        if (!empty($_FILES['imagen']['name'])) {

            $carpetaDestino = 'uploads/productos/';

            if (!is_dir($carpetaDestino)) {
                if (!mkdir($carpetaDestino, 0755, true)) {
                    throw new Exception("No se pudo crear la carpeta '$carpetaDestino'.");
                }
            }

            if (!is_writable($carpetaDestino)) {
                if (!chmod($carpetaDestino, 0755) || !is_writable($carpetaDestino)) {
                    throw new Exception("La carpeta '$carpetaDestino' no tiene permisos de escritura.");
                }
            }

            $nombreArchivo = $_FILES['imagen']['name'];
            $tamanoArchivo = $_FILES['imagen']['size'];
            $errorArchivo = $_FILES['imagen']['error'];

            if ($errorArchivo !== UPLOAD_ERR_OK) {
                throw new Exception("Error al subir la imagen '$nombreArchivo'.");
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

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFinal)) {
                throw new Exception("No se pudo guardar la imagen '$nombreArchivo'.");
            }

            $rutaImagen = $rutaFinal;

            // Si es actualización, eliminar imagen anterior
            if ($producto_id > 0) {
                $stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
                $stmt->execute([$producto_id]);
                $imgAnterior = $stmt->fetchColumn();
                if ($imgAnterior && file_exists($imgAnterior)) {
                    unlink($imgAnterior);
                }
            }
        }

        if ($producto_id > 0) {
            // -------------------------
            // Actualizar producto
            // -------------------------
            if ($rutaImagen) {
                $stmt = $pdo->prepare("UPDATE productos 
                                   SET nombre = ?, descripcion = ?, precio_unitario = ?, stock = ?, imagen = ? 
                                   WHERE id = ?");
                $stmt->execute([$nombre, $descripcion, $precio_unitario, $stock, $rutaImagen, $producto_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE productos 
                                   SET nombre = ?, descripcion = ?, precio_unitario = ?, stock = ? 
                                   WHERE id = ?");
                $stmt->execute([$nombre, $descripcion, $precio_unitario, $stock, $producto_id]);
            }

        } else {
            // -------------------------
            // Registrar nuevo producto
            // -------------------------
            $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio_unitario, stock, imagen)
                               VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $precio_unitario, $stock, $rutaImagen]);
            $producto_id = $pdo->lastInsertId();
        }

        $pdo->commit();
        $response['success'] = true;
        $response['message'] = $producto_id > 0 ? 'Producto actualizado correctamente.' : 'Producto registrado correctamente.';

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido.';

    echo json_encode($response);
}

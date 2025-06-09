<?php
require '../config/conexion.php';
header('Content-Type: application/json; charset=utf-8');

$idProduccion = isset($_POST['id']) ? (int) $_POST['id'] : null;
$nuevoEstado = isset($_POST['estado']) ? trim($_POST['estado']) : null;
$fotoProducto = $_FILES['foto'] ?? null;
$nuevoStock = isset($_POST['stock_actual']) ? (int) $_POST['stock_actual'] : null;

if (!$idProduccion || $nuevoEstado !== 'finalizado') {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos o estado no permitido.']);
    exit;
}

// Función para normalizar nombres
function normalizarTexto($texto) {
    $texto = mb_strtolower($texto, 'UTF-8');
    $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
    $texto = preg_replace('/[^a-z0-9\s]/', '', $texto);
    $texto = preg_replace('/\s+/', ' ', $texto);
    return trim($texto);
}

// Manejo de imagen (opcional)
$nombreFoto = null;
if ($fotoProducto && $fotoProducto['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($fotoProducto['error'] === UPLOAD_ERR_OK) {
        $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
        $mimePermitidos = ['image/jpeg', 'image/png', 'image/webp'];

        $ext = strtolower(pathinfo($fotoProducto['name'], PATHINFO_EXTENSION));
        $mime = mime_content_type($fotoProducto['tmp_name']);

        if (in_array($ext, $permitidos) && in_array($mime, $mimePermitidos)) {
            $directorio = 'uploads/productos';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }

            $nombreFoto =  $directorio . '/' . uniqid('producto_', true) . '.' . $ext;
            if (!move_uploaded_file($fotoProducto['tmp_name'], $nombreFoto)) {
                echo json_encode(['success' => false, 'message' => 'Error al guardar la imagen.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Formato de imagen no permitido.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al subir la imagen.']);
        exit;
    }
}

// 1. Actualizar producción
$stmt = $pdo->prepare("
    UPDATE producciones 
    SET estado_id = (
        SELECT id FROM estados WHERE nombre = 'finalizado' AND entidad = 'produccion'
    ), fecha_fin = CURDATE()
    WHERE id = ?
");
$stmt->execute([$idProduccion]);

// 2. Obtener pedido relacionado
$stmt = $pdo->prepare("
    SELECT p.id, p.proyecto 
    FROM pedidos p 
    JOIN producciones pr ON pr.solicitud_id = p.id 
    WHERE pr.id = ?
");
$stmt->execute([$idProduccion]);
$pedido = $stmt->fetch();
$idPedido = $pedido['id'] ?? null;
$proyecto = $pedido['proyecto'] ?? null;

// 3. Actualizar pedido si existe
if ($idPedido) {
    $stmt = $pdo->prepare("
        UPDATE pedidos 
        SET estado_id = (
            SELECT id FROM estados WHERE nombre = 'finalizado' AND entidad = 'pedido'
        ) WHERE id = ?
    ");
    $stmt->execute([$idPedido]);
}

$resumen = [];

// 4. Asociar imagen y stock al producto si corresponde
if ($nombreFoto && $proyecto) {
    $nombreProyecto = normalizarTexto($proyecto);

    $stmtProd = $pdo->prepare("SELECT id, nombre FROM productos");
    $stmtProd->execute();
    $productos = $stmtProd->fetchAll();

    foreach ($productos as $producto) {
        if (normalizarTexto($producto['nombre']) === $nombreProyecto) {
            $pdo->prepare("UPDATE productos SET imagen = ? WHERE id = ?")
                ->execute([$nombreFoto, $producto['id']]);

            if (!is_null($nuevoStock)) {
                $pdo->prepare("UPDATE productos SET stock = ? WHERE id = ?")
                    ->execute([$nuevoStock, $producto['id']]);
                $resumen[] = "Stock actualizado a $nuevoStock unidades.";
            }
            break;
        }
    }
}

// 5. Final
echo json_encode([
    'success' => true,
    'message' => 'Producción finalizada correctamente.',
    'resumen' => $resumen
]);

<?php
require '../config/conexion.php';

// Configurar cabecera de respuesta JSON segura
header('Content-Type: application/json; charset=utf-8');

// Validar y sanitizar inputs
$idProduccion = isset($_POST['id']) ? (int) $_POST['id'] : null;
$nuevoEstado = isset($_POST['estado']) ? trim($_POST['estado']) : null;
$fotoProducto = $_FILES['foto'] ?? null;
$nuevoStock = isset($_POST['stock_actual']) ? (int) $_POST['stock_actual'] : null;

if (!$idProduccion || $nuevoEstado !== 'finalizado') {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos o estado no permitido.']);
    exit;
}

// Función auxiliar segura para normalizar texto
function normalizarTexto($texto) {
    $texto = mb_strtolower($texto, 'UTF-8');
    $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto); // Elimina acentos
    $texto = preg_replace('/[^a-z0-9\s]/', '', $texto); // Solo letras y números
    $texto = preg_replace('/\s+/', ' ', $texto); // Espacios múltiples
    return trim($texto);
}

// Subir imagen del producto (si aplica)
$nombreFoto = null;
if ($fotoProducto && $fotoProducto['error'] === UPLOAD_ERR_OK) {
    $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
    $mimePermitidos = ['image/jpeg', 'image/png', 'image/webp'];

    $ext = strtolower(pathinfo($fotoProducto['name'], PATHINFO_EXTENSION));
    $mime = mime_content_type($fotoProducto['tmp_name']);

    if (in_array($ext, $permitidos) && in_array($mime, $mimePermitidos)) {
        $directorio = realpath('uploads/productos');
        if (!$directorio) {
            mkdir('uploads/productos', 0755, true);
        }

        $nombreFoto = uniqid('uploads/productos/producto_', true) . '.' . $ext;
        $destino = 'uploads/productos/' . $nombreFoto;

        if (!move_uploaded_file($fotoProducto['tmp_name'], $destino)) {
            echo json_encode(['success' => false, 'message' => 'Error al guardar la imagen.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Formato de imagen no permitido.']);
        exit;
    }
}

// 1. Marcar producción como finalizada
$stmt = $pdo->prepare("
    UPDATE producciones 
    SET estado_id = (
        SELECT id FROM estados 
        WHERE nombre = 'finalizado' AND entidad = 'produccion'
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

// 3. Actualizar estado del pedido
if ($idPedido) {
    $stmt = $pdo->prepare("
        UPDATE pedidos 
        SET estado_id = (
            SELECT id FROM estados 
            WHERE nombre = 'finalizado' AND entidad = 'pedido'
        ) WHERE id = ?
    ");
    $stmt->execute([$idPedido]);
}

// 4. Calcular materiales devueltos
$stmt = $pdo->prepare("
    SELECT d.material_id, d.cantidad AS cantidad_solicitada,
           COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'salida' THEN m.cantidad ELSE 0 END), 0) AS cantidad_movida
    FROM detalles_pedido_material d
    LEFT JOIN movimientos_material m ON m.material_id = d.material_id AND m.produccion_id = ?
    WHERE d.pedido_id = ?
    GROUP BY d.material_id
");
$stmt->execute([$idProduccion, $idPedido]);
$materiales = $stmt->fetchAll();

$resumen = [];

foreach ($materiales as $mat) {
    $restante = $mat['cantidad_solicitada'] - $mat['cantidad_movida'];
    if ($restante > 0) {
        // Movimiento de entrada automática
        $stmt = $pdo->prepare("
            INSERT INTO movimientos_material (material_id, tipo_movimiento, cantidad, motivo, produccion_id)
            VALUES (?, 'entrada', ?, ?, ?)
        ");
        $stmt->execute([
            $mat['material_id'],
            $restante,
            'Ajuste automático por finalización de producción con material sobrante',
            $idProduccion
        ]);

        // Actualizar stock del material
        $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual + ? WHERE id = ?")
            ->execute([$restante, $mat['material_id']]);

        $resumen[] = "Material ID {$mat['material_id']}: devolución de $restante unidades.";
    }
}

// 5. Asociar imagen a producto si corresponde
if ($nombreFoto && $proyecto) {
    $nombreProyecto = normalizarTexto($proyecto);
    $stmtProd = $pdo->prepare("SELECT id, nombre FROM productos");
    $stmtProd->execute();
    $productos = $stmtProd->fetchAll();

    foreach ($productos as $producto) {
        if (normalizarTexto($producto['nombre']) === $nombreProyecto) {
            // Asociar imagen
            $pdo->prepare("UPDATE productos SET imagen = ? WHERE id = ?")
                ->execute([$nombreFoto, $producto['id']]);

            // Actualizar stock si se proporcionó
            if (!is_null($nuevoStock)) {
                $pdo->prepare("UPDATE productos SET stock = ? WHERE id = ?")
                    ->execute([$nuevoStock, $producto['id']]);
                $resumen[] = "Stock del producto actualizado a $nuevoStock unidades.";
            }
            break;
        }
    }
}

// 6. Respuesta final
echo json_encode([
    'success' => true,
    'message' => 'Producción finalizada correctamente.',
    'resumen' => $resumen
]);

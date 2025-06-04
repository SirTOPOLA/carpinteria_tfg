<?php
require '../config/conexion.php';
header('Content-Type: application/json');

$idProduccion = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($idProduccion <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de producción no válido']);
    exit;
}

try {
    // Obtener el pedido asociado a la producción
    $sqlPedido = "SELECT solicitud_id FROM producciones WHERE id = :id";
    $stmt = $pdo->prepare($sqlPedido);
    $stmt->execute([':id' => $idProduccion]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Producción no encontrada']);
        exit;
    }

    $pedidoId = $row['solicitud_id'];

    // Obtener materiales del pedido
    $sql = "SELECT 
                m.id AS material_id,
                m.nombre AS material,
                m.unidad_medida,
                m.stock_actual,
                dpm.cantidad
            FROM detalles_pedido_material dpm
            INNER JOIN materiales m ON dpm.material_id = m.id
            WHERE dpm.pedido_id = :pedidoId";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':pedidoId' => $pedidoId]);
    $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$materiales) {
        echo json_encode(['success' => false, 'message' => 'No hay materiales asociados al pedido']);
        exit;
    }

    // Generar HTML
    $html = '<div class="table-responsive">';
    $html .= '<table class="table table-bordered table-sm align-middle">';
    $html .= '<thead class="table-light">
                <tr>
                    <th>Material</th>
                    <th>Unidad</th>
                    <th>Requerido</th>
                    <th>Stock Actual</th>
                    <th>Cantidad a mover</th>
                    <th>Motivo</th>
                    <th>Acción</th>
                </tr>
              </thead><tbody>';

    foreach ($materiales as $mat) {
        $materialId = $mat['material_id'];
        $html .= "<tr>
            <td>{$mat['material']}</td>
            <td>{$mat['unidad_medida']}</td>
            <td>{$mat['cantidad']}</td>
            <td><span class='badge bg-".($mat['stock_actual'] >= $mat['cantidad'] ? "success" : "danger")."'>
                    {$mat['stock_actual']}
                </span></td>
            <td><input type='number' class='form-control form-control-sm cantidad-mover' min='1' max='{$mat['stock_actual']}' value='{$mat['cantidad']}' 
                    data-material-id='$materialId'></td>
            <td><input type='text' class='form-control form-control-sm motivo-mover' 
                    placeholder='Motivo del movimiento' data-material-id='$materialId'></td>
            <td>
                <button class='btn btn-sm btn-outline-primary mover-material-btn' 
                        data-material-id='$materialId'
                        data-produccion-id='$idProduccion'>
                    <i class='bi bi-arrow-right-circle'></i> Mover
                </button>
            </td>
        </tr>";
    }

    $html .= '</tbody></table></div>';

    echo json_encode(['success' => true, 'html' => $html]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
}

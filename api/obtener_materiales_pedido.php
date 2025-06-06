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
                dpm.cantidad AS cantidad_solicitada,
                COALESCE((
                    SELECT SUM(cantidad) 
                    FROM movimientos_material mm 
                    WHERE mm.material_id = dpm.material_id 
                    AND mm.produccion_id = :produccionId 
                    AND mm.tipo_movimiento = 'salida'
                ), 0) AS cantidad_movida
            FROM detalles_pedido_material dpm
            INNER JOIN materiales m ON dpm.material_id = m.id
            WHERE dpm.pedido_id = :pedidoId";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':pedidoId' => $pedidoId, ':produccionId' => $idProduccion]);
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
        $restante = $mat['cantidad_solicitada'] - $mat['cantidad_movida'];
        $stockSuficiente = $mat['stock_actual'] >= $restante;

        // Solo deshabilitar inputs y botón si no queda restante (por defecto es 'salida')
        $deshabilitarInputsBtn = ($restante <= 0) ? 'disabled' : '';

        $badgeColor = $stockSuficiente ? 'success' : 'danger';

        $html .= "<tr>
                    <td>{$mat['material']}</td>
                    <td>{$mat['unidad_medida']}</td>
                    <td>
                        <span class='text-primary fw-semibold restante' data-material-id='$materialId' data-restante='$restante'>
                            $restante / {$mat['cantidad_solicitada']}
                        </span>
                    </td>
                    <td>
                        <span class='badge bg-$badgeColor'>
                            {$mat['stock_actual']}
                        </span>
                    </td>
                    <td>
                        <input type='number' class='form-control form-control-sm cantidad-mover' 
                            min='1' max='$restante' value='" . ($restante > 0 ? $restante : 0) . "' 
                            data-material-id='$materialId' $deshabilitarInputsBtn>
                    </td>
                    <td>
                        <input type='text' class='form-control form-control-sm motivo-mover' 
                            placeholder='Motivo del movimiento' data-material-id='$materialId' $deshabilitarInputsBtn>
                    </td>
                    <td>
                        <div class='d-flex gap-1 align-items-center'>
                            <select class='form-select form-select-sm tipo-movimiento' data-material-id='$materialId'>
                                <option value='salida' selected>Salida</option>
                                <option value='entrada'>Entrada</option>
                            </select>
                            <button class='btn btn-sm btn-outline-primary mover-material-btn' 
                                data-material-id='$materialId'
                                data-produccion-id='$idProduccion'
                                $deshabilitarInputsBtn>
                                <i class='bi bi-arrow-right-circle icono-movimiento'></i> Mover
                            </button>
                        </div>
                    </td>
                </tr>";
    }


    $html .= '</tbody></table></div>';

    echo json_encode(['success' => true, 'html' => $html]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
}
